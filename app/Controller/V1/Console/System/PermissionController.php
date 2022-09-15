<?php


namespace App\Controller\V1\Console\System;


use App\Helper\Common;
use App\Model\Permission;
use App\Request\V1\Console\System\Permission\CreatedRequest;
use App\Request\V1\Console\System\Permission\DeletedRequest;
use App\Request\V1\Console\System\Permission\ItemsRequest;
use App\Request\V1\Console\System\Permission\UpdatedRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\ConsoleMiddleware;
use App\Controller\AbstractController;

/**
 * Class PermissionController
 * @package App\Controller\V1\Console\System
 * @AutoController()
 * @Middlewares({
 *     @Middleware(ConsoleMiddleware::class)
 * })
 */
class PermissionController extends AbstractController
{
    /**
     * @var Common
     * @Inject()
     */
    private $common;

    public function list() {
        $user = $this->request->getAttribute('user');
        $result = parallel([
            function () use ($user) {
                $res = Permission::query()->where('project_id', $user['project_id'])->where('status', 80)->select('id', 'parent_id', 'title', 'name', 'path', 'component', 'redirect', 'display_name', 'url', 'icon', 'guard_name', 'sort', 'type', 'status')->orderBy('sort')->orderBy('created_at')->get();
                foreach ($res as $k => $v) {
                    $res[$k]['value'] = $v['id'];
                }
                return $this->common->treePermissionNode($res->toArray());
            },
            function () use ($user) {
                return Permission::query()->where('project_id', $user['project_id'])->orderBy('id')->pluck('id');
            },
        ]);

        return [
            'data' => $result[0],
            'key' => $result[1],
            'success' => true
        ];
    }

    public function created(CreatedRequest $request) {
        $data = $request->all();
        $data['name'] = $data['type'] === 10 ? $data['name'] ?? '' : $data['name'] ?? $data['path'];
        $data['sort'] = isset($data['sort']) ? (int)$data['sort'] : 0;
        $res = Permission::create($data);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function updated(UpdatedRequest $request) {
        $data = $request->all();
        if ($data['type'] === 20) {
            $data['name'] = $data['path'];
        }
        $permission = Permission::query()->find($data['id']);
//        if ($permission['path'] !== $data['path']) {
//            return $this->fail('无法更新地址');
//        }
        $res= $permission->update($data);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function deleted(DeletedRequest $request) {
        $data = $request->all();
        $res = Permission::query()->where('id', $data['id'])->delete();
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function items(ItemsRequest $request){
        $data = $request->all();
        $res = Permission::query()
            ->where('type', 10)
            ->where('status', 80)
            ->select('id', 'parent_id', 'title', 'sort')
            ->orderBy('sort')
            ->get();
        foreach ($res as $k => $v) {
            $res[$k]['value'] = $v['id'];
        }
        $nodes = $res->toArray();
        $res = $this->common->treeNodeRemove($nodes, $data['id']);
        $list = $this->common->treePermissionNode($res);
        return $this->success(['list' => $list]);
    }
}