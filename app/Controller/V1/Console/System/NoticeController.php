<?php


namespace App\Controller\V1\Console\System;


use App\Controller\AbstractController;
use App\Helper\Common;
use App\Model\Department;
use App\Model\Notice;
use App\Model\User;
use App\Model\UserNotice;
use App\Request\V1\Console\System\Notice\CreatedRequest;
use App\Request\V1\Console\System\Notice\DeletedRequest;
use App\Request\V1\Console\System\Notice\WithdrawRequest;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\ConsoleMiddleware;

/**
 * Class NoticeController
 * @package App\Controller\V1\Console\System
 * @AutoController()
 * @Middlewares({
 *     @Middleware(ConsoleMiddleware::class)
 * })
 */
class NoticeController extends AbstractController
{
    /**
     * @var Common
     * @Inject()
     */
    private $common;

    public function list()
    {
        $user = $this->request->getAttribute('user');
        $request = $this->request->all();
        $current = isset($request['current']) ? (int)$request['current'] : 1;
        $pageSize = isset($request['pageSize']) ? (int)$request['pageSize'] : 10;
        $where = [];
        $sorter = 'created_at_descend';
        if ($user['project_id']) {
            $where[] = ['project_id', $user['project_id']];
        }
        if (isset($request['created_at'])) {
            switch ($request['created_at']) {
                case 'ascend':
                    $sorter = 'created_at_ascend';
                    break;
                default:
                    $sorter = 'created_at_descend';
            }
        }
        isset($request['title']) && $where[] = ['title', 'like', "%{$request['name']}%"];
        isset($request['status']) && $where[] = ['status', $request['status']];
        isset($request['startTime']) && $where[] = ['created_at', '>', $request['startTime']];
        isset($request['endTime']) && $where[] = ['created_at', '<', $request['endTime']];
        $result = parallel([
            function () use ($sorter, $where, $current, $pageSize) {
                switch ($sorter) {
                    case 'created_at_descend':
                        $list = Notice::query()
                            ->where($where)
                            ->forPage($current, $pageSize)
                            ->orderByDesc('created_at')
                            ->get();
                        break;
                    default:
                        $list = Notice::query()
                            ->where($where)
                            ->forPage($current, $pageSize)
                            ->orderBy('created_at')
                            ->get();
                }
                return $list ?: [];
            },
            function () use ($where) {
                $total = Notice::query()
                    ->where($where)
                    ->count();
                return $total ?: 0;
            },
        ]);
        return [
            'data' => $result[0],
            'total' => $result[1],
            'success' => true,
            'pageSize' => $pageSize,
            'current' => $current
        ];

    }

    public function created(CreatedRequest $request) {
        $data = $request->all();
        $user = $this->request->getAttribute('user');
        $where = [];
        if ($user['project_id']) {
            $where[] = ['project_id', $user['project_id']];
        }
        switch ($data['type']) {
            case 20:
                $users = User::query()->where($where)->whereIn('department_id', $data['key'])->get();
                break;
            case 30:
                $users = User::query()->where($where)->whereIn('id', $data['key'])->get();
                break;
            default:
                $users = User::query()->where($where)->get();
        }
        Db::beginTransaction();
        $mod = new Notice();
        $mod->project_id = $user['project_id'];
        $mod->title = $data['title'];
        $mod->content = $data['content'];
        isset($data['files']) && $mod->files = $data['files'];
        isset($data['type']) && $mod->type = $data['type'];
        $mod->status = 80;
        $res = $mod->save();
        if (!$res) {
            Db::rollBack();
            return $this->fail();
        }
        foreach ($users as $user){
            $user_notice_mod = new UserNotice();
            $user_notice_mod->user_id = $user['id'];
            $user_notice_mod->notice_id = $mod->getKey();
            $user_notice_mod->project_id = $user['project_id'];
            $res = $user_notice_mod->save();
            if (!$res) {
                Db::rollBack();
                return $this->fail();
            }
        }
        Db::commit();
        return $this->success();
    }

    public function deleted(DeletedRequest $request) {
        $data = $request->all();
        Db::beginTransaction();
        $res = is_array($data['key']) ?
            Notice::query()->whereIn('id', $data['key'])->delete() :
            Notice::query()->where('id', $data['key'])->delete();
        if (!$res) {
            Db::rollBack();
            return $this->fail();
        }
        $res = is_array($data['key']) ?
            UserNotice::query()->whereIn('notice_id', $data['key'])->delete() :
            UserNotice::query()->where('notice_id', $data['key'])->delete();
        if (!$res) {
            Db::rollBack();
            return $this->fail();
        }
        Db::commit();
        return $this->success();
    }

    public function withdraw(WithdrawRequest $request) {
        $data = $request->all();
        Db::beginTransaction();
        $res = Notice::query()->where('id', $data['id'])->update(['status' => 40, 'remark' => '后台撤回']);
        if (!$res) {
            Db::rollBack();
            return $this->fail();
        }
        $res = UserNotice::query()->where('notice_id', $data['id'])->update(['status' => 40]);
        if (!$res) {
            Db::rollBack();
            return $this->fail();
        }
        Db::commit();
        return $this->success();
    }

    public function department() {
        $user = $this->request->getAttribute('user');
        $where = [];
        if ($user['project_id']) {
            $where[] = ['project_id', $user['project_id']];
        }
        $res = Department::query()->where($where)->select('id', 'parent_id', 'name')->get();
        foreach ($res as $k => $v) {
            $res[$k]['title'] = $v['name'];
            $res[$k]['value'] = $v['id'];
        }
        return $this->success($this->common->treeNode($res->toArray()));
    }

    public function user() {
        $user = $this->request->getAttribute('user');
        $where = [];
        if ($user['project_id']) {
            $where[] = ['project_id', $user['project_id']];
        }
        $list = [];
        $res = User::query()->where($where)->where('status', 80)->get();
        foreach ($res as $k => $v) {
            $list[$k]['value'] = $v['id'];
            $list[$k]['label'] = $v['real_name'];
        }
        return $this->success($list);
    }
}