<?php


namespace App\Controller\V1\Console\System;


use App\Controller\AbstractController;
use App\Helper\Common;
use App\Model\Department;
use App\Model\Log;
use App\Model\Project;
use App\Request\V1\Console\System\Log\DeletedRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\ConsoleMiddleware;

/**
 * Class LogController
 * @package App\Controller\V1\Console\System
 * @AutoController()
 * @Middlewares({
 *     @Middleware(ConsoleMiddleware::class)
 * })
 */
class LogController extends AbstractController
{
    /**
     * @var Common
     * @Inject()
     */
    private $common;

    public function list() {
        $user = $this->request->getAttribute('user');
        $request = $this->request->all();
        $current = isset($request['current']) ? (int)$request['current'] : 1;
        $pageSize = isset($request['pageSize']) ? (int)$request['pageSize'] : 20;
        $sorter = $request['sorter'] ?? 'created_at_descend';
        $where = [];
        if ($user['project_id']) {
            $where = [
                ['log.project_id', $user['project_id']]
            ];
        }
        isset($request['real_name']) && $where[] = ['user.real_name', 'like', "%{$request['real_name']}%"];
        isset($request['username']) && $where[] = ['user.username', 'like', "%{$request['username']}%"];
        isset($request['title']) && $where[] = ['log.title', 'like', "%{$request['title']}%"];
        isset($request['project_id']) && $where[] = ['log.project_id', $request['project_id']];
        isset($request['department_id']) && $where[] = ['log.department_id', $request['department_id']];
        isset($request['startTime']) && $where[] = ['log.created_at', '>', $request['startTime']];
        isset($request['endTime']) && $where[] = ['log.created_at', '<', $request['endTime']];
        $result = parallel([
            function () use ($sorter, $where, $current, $pageSize) {
                switch ($sorter) {
                    case 'created_at_descend':
                        $list = Log::query()
                            ->where($where)
                            ->leftJoin('user', 'user.id', '=', 'log.user_id')
                            ->leftJoin('project', 'project.id', '=', 'log.project_id')
                            ->leftJoin('department', 'department.id', '=', 'log.department_id')
                            ->select(
                                'log.*',
                                'user.username',
                                'user.mobile',
                                'user.real_name',
                                'project.name as project_name',
                                'department.name as department_name',
                            )
                            ->forPage($current, $pageSize)
                            ->orderByDesc('log.created_at')
                            ->get();
                        break;
                    default:
                        $list = Log::query()
                            ->where($where)
                            ->leftJoin('user', 'user.id', '=', 'log.user_id')
                            ->leftJoin('project', 'project.id', '=', 'log.project_id')
                            ->leftJoin('department', 'department.id', '=', 'log.department_id')
                            ->select(
                                'log.*',
                                'user.username',
                                'user.mobile',
                                'user.real_name',
                                'project.name as project_name',
                                'department.name as department_name',
                            )
                            ->forPage($current, $pageSize)
                            ->orderBy('gardens_log.created_at')
                            ->get();
                }
                return $list ?: [];
            },
            function () use ($where) {
                $total = Log::query()
                    ->where($where)
                    ->leftJoin('user', 'user.id', '=', 'log.user_id')
                    ->leftJoin('project', 'project.id', '=', 'log.project_id')
                    ->leftJoin('department', 'department.id', '=', 'log.department_id')
                    ->select(
                        'log.*',
                        'user.username',
                        'user.mobile',
                        'user.real_name',
                        'project.name as project_name',
                        'department.name as department_name',
                    )
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

    public function deleted(DeletedRequest $request) {
        $data = $request->all();
        $res = is_array($data['key']) ?
            Log::query()->whereIn('id', $data['key'])->delete() :
            Log::query()->where('id', $data['key'])->delete();
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function options() {
        $result = parallel([
            function () {
                return Project::query()->where('status', 80)->get();
            },
            function () {
                $list =  Department::query()->where('status', 80)->get();
                foreach ($list as $k => $v) {
                    $list[$k]['value'] = $v['id'];
                    $list[$k]['title'] = $v['name'];
                }
                return $this->common->treeNode($list->toArray());
            },
        ]);

        return $this->success([
            'project' => $result[0],
            'department' => $result[1]
        ]);
    }
}