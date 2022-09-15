<?php


namespace App\Controller\V1\Console\System;


use App\Controller\AbstractController;
use App\Helper\Common;
use App\Model\Permission;
use App\Model\Project;
use App\Model\Role;
use App\Request\V1\Console\System\Role\BindRequest;
use App\Request\V1\Console\System\Role\ClearRequest;
use App\Request\V1\Console\System\Role\CreatedRequest;
use App\Request\V1\Console\System\Role\DeletedRequest;
use App\Request\V1\Console\System\Role\PermissionRequest;
use App\Request\V1\Console\System\Role\UpdatedRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\ConsoleMiddleware;
use Donjan\Casbin\Enforcer;

/**
 * Class RoleController
 * @package App\Controller\V1\Console\System
 * @AutoController()
 * @Middlewares({
 *     @Middleware(ConsoleMiddleware::class)
 * })
 */
class RoleController extends AbstractController
{
    /**
     * @var Common
     * @Inject()
     */
    private $common;

    public function list() {
        $request = $this->request->all();
        $current = isset($request['current']) ? (int)$request['current'] : 1;
        $pageSize = isset($request['pageSize']) ? (int)$request['pageSize'] : 10;
        $sorter = $request['sorter'] ?? 'created_at_descend';
        $where = [];
        isset($request['name']) && $where[] = ['role.name', 'like', "%{$request['name']}%"];
        isset($request['description']) && $where[] = ['role.description', 'like', "%{$request['description']}%"];
        isset($request['code']) && $where[] = ['role.code', $request['code']];
        isset($request['project_id']) && $where[] = ['role.project_id', $request['project_id']];
        isset($request['status']) && $where[] = ['role.status', $request['status']];
        isset($request['startTime']) && $where[] = ['role.created_at', '>', $request['startTime']];
        isset($request['endTime']) && $where[] = ['role.created_at', '<', $request['endTime']];
        $result = parallel([
            function () use ($sorter, $where, $current, $pageSize) {
                switch ($sorter) {
                    case 'created_at_descend':
                        $list = Role::query()
                            ->where($where)
                            ->leftJoin('project', 'project.id', '=', 'role.project_id')
                            ->select('role.*', 'project.name as project_name')
                            ->forPage($current, $pageSize)
                            ->orderByDesc('role.created_at')
                            ->get();
                        break;
                    default:
                        $list = Role::query()
                            ->where($where)
                            ->leftJoin('project', 'project.id', '=', 'role.project_id')
                            ->select('role.*', 'project.name as project_name')
                            ->forPage($current, $pageSize)
                            ->orderBy('role.created_at')
                            ->get();
                }
                return $list ?: [];
            },
            function () use ($where) {
                $total = Role::query()
                    ->where($where)
                    ->leftJoin('project', 'project.id', '=', 'role.project_id')
                    ->select('role.*', 'project.name as project_name')
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
        $role = Role::query()->where('code', $data['code'])->first();
        if ($role) {
            return $this->fail('标识重复，请更换');
        }
        $res = Role::create($data);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function updated(UpdatedRequest $request) {
        $data = $request->all();
        $role = Role::query()->where('code', $data['code'])->where('id', '<>', $data['id'])->first();
        if ($role) {
            return $this->fail('标识重复，请更换');
        }
        $res= Role::query()->where('id', $data['id'])->update($data);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function deleted(DeletedRequest $request) {
        $data = $request->all();
        $res = is_array($data['key']) ?
            Role::query()->whereIn('id', $data['key'])->get() :
            Role::query()->where('id', $data['key'])->get();
        foreach ($res as $v) {
            $v->delete();
            Enforcer::deletePermissionsForUser($v['code']);
        }
        return $this->success();
    }

    public function bind(BindRequest $request) {
        $data = $request->all();
        $role = Role::query()->where('id', $data['id'])->first();
        Enforcer::deletePermissionsForUser($role['code']);
        foreach ($data['permissions'] as $id) {
            $permission = Permission::query()->find($id);
            Enforcer::addPermissionForUser($role['code'], (string)$permission['id'], $permission['path']);
        }
        return $this->success();
    }

    public function clear(ClearRequest $request) {
        $data = $request->all();
        $role = Role::query()->where('id', $data['id'])->first();
        if (!$role) {
            return $this->fail();
        }
        $res = Enforcer::deletePermissionsForUser($role['code']);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function permission(PermissionRequest $request) {
        $data = $request->all();
        $user = $this->request->getAttribute('user');
        $result = parallel([
            function () use($user) {
                $res = Permission::query()->where('project_id', $user['project_id'])->where('status', 80)->select('id', 'title', 'parent_id')->orderBy('sort')->get();
                foreach ($res as $k => $v) {
                    $res[$k]['key'] = $v['id'];
                }
                return $this->common->treeNode($res->toArray());
            },
            function () use($user) {
                return Permission::query()->where('project_id', $user['project_id'])->orderBy('id')->pluck('id');
            },
            function () use($user, $data) {
                $list = [];
                $res = Enforcer::getPermissionsForUser($data['code']);
                foreach ($res as $k => $v) {
                    $list[$k] = (int)$v[1];
                }
                return $list;
            },
        ]);
        return $this->success([
            'list' => $result[0],
            'keys' => $result[1],
            'info' => $result[2],
            'success' => true
        ]);
    }

    public function project() {
        $list = Project::query()->where('status', 80)->get();
        return $this->success($list);
    }
}