<?php


namespace App\Controller\V1\Console\System;


use App\Controller\AbstractController;
use App\Helper\Common;
use App\Model\Department;
use App\Model\Permission;
use App\Model\Project;
use App\Model\Role;
use App\Model\User;
use App\Request\V1\Console\System\User\ClearRequest;
use App\Request\V1\Console\System\User\BindRequest;
use App\Request\V1\Console\System\User\PermissionRequest;
use App\Request\V1\Console\System\User\CreatedRequest;
use App\Request\V1\Console\System\User\FreezeRequest;
use App\Request\V1\Console\System\User\UpdatedRequest;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\ConsoleMiddleware;
use Illuminate\Hashing\BcryptHasher;
use Donjan\Casbin\Enforcer;

/**
 * Class UserController
 * @package App\Controller\V1\Console\System
 * @AutoController()
 * @Middlewares({
 *     @Middleware(ConsoleMiddleware::class)
 * })
 */
class UserController extends AbstractController
{
    /**
     * @var Common
     * @Inject()
     */
    private $common;

    /**
     * @var BcryptHasher
     * @Inject()
     */
    private $hash;

    public function list() {
        $user = $this->request->getAttribute('user');
        $request = $this->request->all();
        $current = isset($request['current']) ? (int)$request['current'] : 1;
        $pageSize = isset($request['pageSize']) ? (int)$request['pageSize'] : 10;
        $sorter = $request['sorter'] ?? 'created_at_descend';
        if ($user['project_id']) {
            $where = [
                ['user.super', '<>', 80],
                ['user.project_id', $user['project_id']]
            ];
        } else {
            $where = [
                ['user.super', '<>', 80]
            ];
        }
        isset($request['real_name']) && $where[] = ['user.real_name', 'like', "%{$request['real_name']}%"];
        isset($request['username']) && $where[] = ['user.username', 'like', "%{$request['username']}%"];
        isset($request['mobile']) && $where[] = ['user.mobile', 'like', "%{$request['mobile']}%"];
        isset($request['role_id']) && $where[] = ['user.role', $request['role_id']];
        isset($request['project_id']) && $where[] = ['user.project_id', $request['project_id']];
        isset($request['department_id']) && $where[] = ['user.department_id', $request['department_id']];
        isset($request['status']) && $where[] = ['user.status', $request['status']];
        isset($request['startTime']) && $where[] = ['user.created_at', '>', $request['startTime']];
        isset($request['endTime']) && $where[] = ['user.created_at', '<', $request['endTime']];
        if (isset($request['role'])) {
            $result = parallel([
                function () use ($sorter, $where, $request, $current, $pageSize) {
                    switch ($sorter) {
                        case 'created_at_descend':
                            $list = User::query()
                                ->where($where)
                                ->whereJsonContains('role', $request['role'])
                                ->leftJoin('project', 'project.id', '=', 'user.project_id')
                                ->leftJoin('department', 'department.id', '=', 'user.department_id')
                                ->select(
                                    'user.id',
                                    'user.project_id',
                                    'user.department_id',
                                    'user.username',
                                    'user.mobile',
                                    'user.real_name',
                                    'user.nick_name',
                                    'user.sex',
                                    'user.birthday',
                                    'user.email',
                                    'user.telephone',
                                    'user.avatar_url',
                                    'user.remark',
                                    'user.type',
                                    'user.status',
                                    'user.type_updated_at',
                                    'user.last_login_at',
                                    'user.created_at',
                                    'user.updated_at',
                                    'user.deleted_at',
                                    'department.name as department_name',
                                    'project.name as project_name'
                                )
                                ->forPage($current, $pageSize)
                                ->orderByDesc('user.created_at')
                                ->get();
                            break;
                        default:
                            $list = User::query()
                                ->where($where)
                                ->whereJsonContains('role', $request['role'])
                                ->leftJoin('project', 'project.id', '=', 'user.project_id')
                                ->leftJoin('department', 'department.id', '=', 'user.department_id')
                                ->select(
                                    'user.id',
                                    'user.project_id',
                                    'user.department_id',
                                    'user.username',
                                    'user.mobile',
                                    'user.real_name',
                                    'user.nick_name',
                                    'user.sex',
                                    'user.birthday',
                                    'user.email',
                                    'user.telephone',
                                    'user.avatar_url',
                                    'user.remark',
                                    'user.type',
                                    'user.status',
                                    'user.type_updated_at',
                                    'user.last_login_at',
                                    'user.created_at',
                                    'user.updated_at',
                                    'user.deleted_at',
                                    'department.name as department_name',
                                    'project.name as project_name'
                                )
                                ->forPage($current, $pageSize)
                                ->orderBy('user.created_at')
                                ->get();
                    }
                    foreach ($list as $k => $v) {
                        $roles = Enforcer::getRolesForUser($v['id']);
                        $role = Role::query()->whereIn('code', $roles)->pluck('name');
                        $list[$k]['role'] = $role ?: [];
                    }
                    return $list ?: [];
                },
                function () use ($where, $request) {
                    $total = User::query()
                        ->where($where)
                        ->whereJsonContains('role', $request['role'])
                        ->leftJoin('project', 'project.id', '=', 'user.project_id')
                        ->leftJoin('department', 'department.id', '=', 'user.department_id')
                        ->select(
                            'user.id',
                            'user.project_id',
                            'user.department_id',
                            'user.username',
                            'user.mobile',
                            'user.real_name',
                            'user.nick_name',
                            'user.sex',
                            'user.birthday',
                            'user.email',
                            'user.telephone',
                            'user.avatar_url',
                            'user.remark',
                            'user.type',
                            'user.status',
                            'user.type_updated_at',
                            'user.last_login_at',
                            'user.created_at',
                            'user.updated_at',
                            'user.deleted_at',
                            'department.name as department_name',
                            'project.name as project_name'
                        )
                        ->count();
                    return $total ?: 0;
                },
            ]);
        } else {
            $result = parallel([
                function () use ($sorter, $where, $current, $pageSize) {
                    switch ($sorter) {
                        case 'created_at_descend':
                            $list = User::query()
                                ->where($where)
                                ->leftJoin('project', 'project.id', '=', 'user.project_id')
                                ->leftJoin('department', 'department.id', '=', 'user.department_id')
                                ->select(
                                    'user.id',
                                    'user.project_id',
                                    'user.department_id',
                                    'user.username',
                                    'user.mobile',
                                    'user.real_name',
                                    'user.nick_name',
                                    'user.sex',
                                    'user.birthday',
                                    'user.email',
                                    'user.telephone',
                                    'user.avatar_url',
                                    'user.remark',
                                    'user.type',
                                    'user.status',
                                    'user.type_updated_at',
                                    'user.last_login_at',
                                    'user.created_at',
                                    'user.updated_at',
                                    'user.deleted_at',
                                    'department.name as department_name',
                                    'project.name as project_name'
                                )
                                ->forPage($current, $pageSize)
                                ->orderByDesc('user.created_at')
                                ->get();
                            break;
                        default:
                            $list = User::query()
                                ->where($where)
                                ->leftJoin('project', 'project.id', '=', 'user.project_id')
                                ->leftJoin('department', 'department.id', '=', 'user.department_id')
                                ->select(
                                    'user.id',
                                    'user.project_id',
                                    'user.department_id',
                                    'user.username',
                                    'user.mobile',
                                    'user.real_name',
                                    'user.nick_name',
                                    'user.sex',
                                    'user.birthday',
                                    'user.email',
                                    'user.telephone',
                                    'user.avatar_url',
                                    'user.remark',
                                    'user.type',
                                    'user.status',
                                    'user.type_updated_at',
                                    'user.last_login_at',
                                    'user.created_at',
                                    'user.updated_at',
                                    'user.deleted_at',
                                    'department.name as department_name',
                                    'project.name as project_name'
                                )
                                ->forPage($current, $pageSize)
                                ->orderBy('user.created_at')
                                ->get();
                    }
                    foreach ($list as $k => $v) {
                        $roles = Enforcer::getRolesForUser($v['id']);
                        $role = Role::query()->whereIn('code', $roles)->pluck('name');
                        $list[$k]['role'] = $role ?: [];
                    }
                    return $list ?: [];
                },
                function () use ($where) {
                    $total = User::query()
                        ->where($where)
                        ->leftJoin('project', 'project.id', '=', 'user.project_id')
                        ->leftJoin('department', 'department.id', '=', 'user.department_id')
                        ->select(
                            'user.id',
                            'user.project_id',
                            'user.department_id',
                            'user.username',
                            'user.mobile',
                            'user.real_name',
                            'user.nick_name',
                            'user.sex',
                            'user.birthday',
                            'user.email',
                            'user.telephone',
                            'user.avatar_url',
                            'user.remark',
                            'user.type',
                            'user.status',
                            'user.type_updated_at',
                            'user.last_login_at',
                            'user.created_at',
                            'user.updated_at',
                            'user.deleted_at',
                            'department.name as department_name',
                            'project.name as project_name'
                        )
                        ->count();
                    return $total ?: 0;
                },
            ]);
        }

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
        if ($data['password'] !== $data['confirm']) {
            return $this->fail('两次密码不一致');
        }
        Db::beginTransaction();
        $mod = new User();
        $mod->department_id = $data['department_id'];
        $mod->username = $data['mobile'];
        $mod->mobile = $data['mobile'];
        $mod->real_name = $data['real_name'];
        $mod->password = $this->hash->make($data['password']);
        isset($data['project_id']) && $mod->project_id = $data['project_id'];
        isset($data['role']) && $mod->role = $data['role'];
        isset($data['sex']) && $mod->sex = $data['sex'];
        isset($data['birthday']) && $mod->birthday = $data['birthday'];
        isset($data['email']) && $mod->email = $data['email'];
        isset($data['telephone']) && $mod->telephone = $data['telephone'];
        isset($data['remark']) && $mod->remark = $data['remark'];
        isset($data['project_id']) ? $mod->super = 10 : $mod->super = 20;
        $res = $mod->save();
        if (!$res) {
            Db::rollBack();
            return $this->fail();
        }
        $uid = $mod->getKey();
        foreach ($data['role'] as $code) {
            $res = Enforcer::addRoleForUser((string)$uid, $code);
            if (!$res) {
                Db::rollBack();
                return $this->fail();
            }
        }
        Db::commit();
        return $this->success();
    }

    public function updated(UpdatedRequest $request) {
        $data = $request->all();
        if (isset($data['password']) && isset($data['confirm'])) {
            if ($data['password'] !== $data['confirm']) {
                return $this->fail('两次密码不一致');
            }
        }
        Db::beginTransaction();
        $user = User::find($data['id']);
        $user->department_id = $data['department_id'];
        $user->username = $data['mobile'];
        $user->mobile = $data['mobile'];
        $user->real_name = $data['real_name'];
        isset($data['project_id']) && $user->project_id = $data['project_id'];
        isset($data['role']) && $user->role = $data['role'];
        isset($data['sex']) && $user->sex = $data['sex'];
        isset($data['birthday']) && $user->birthday = $data['birthday'];
        isset($data['email']) && $user->email = $data['email'];
        isset($data['telephone']) && $user->telephone = $data['telephone'];
        isset($data['remark']) && $user->remark = $data['remark'];
        isset($data['project_id']) ? $user->super = 10 : $user->super = 20;
        $res = $user->save();
        if (!$res) {
            Db::rollBack();
            return $this->fail();
        }
        $res = Enforcer::deleteRolesForUser((string)$user['id']);
        if (!$res) {
            Db::rollBack();
            return $this->fail();
        }
        foreach ($data['role'] as $code) {
            $res = Enforcer::addRoleForUser((string)$user['id'], $code);
            if (!$res) {
                Db::rollBack();
                return $this->fail();
            }
        }
        Db::commit();
        return $this->success();
    }

    public function freeze(FreezeRequest $request) {
        $data = $request->all();
        $res = is_array($data['key']) ?
            User::query()->whereIn('id', $data['key'])->update(['status' => 40]) :
            User::query()->where('id', $data['key'])->update(['status' => 40]);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function unfreeze(FreezeRequest $request) {
        $data = $request->all();
        $res = is_array($data['key']) ?
            User::query()->whereIn('id', $data['key'])->update(['status' => 80]) :
            User::query()->where('id', $data['key'])->update(['status' => 80]);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function bind(BindRequest $request) {
        $data = $request->all();
        $user = $this->request->getAttribute('user');
        if ($user['project_id']) {
            return $this->fail();
        }
        $res = User::query()->find($data['id']);
        Enforcer::deletePermissionsForUser((string)$res['id']);
        $permissions = Permission::query()->whereIn('id', $data['permissions'])->get();
        foreach ($permissions as $permission) {
            Enforcer::addPermissionForUser((string)$res['id'], (string)$permission['id'], $permission['path']);
        }
        return $this->success();
    }

    public function clear(ClearRequest $request) {
        $data = $request->all();
        $user = User::query()->find($data['id']);
        if (!$user) {
            return $this->fail();
        }
        $res = Enforcer::deletePermissionsForUser((string)$user['id']);
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
                $res = Enforcer::getPermissionsForUser((string)$data['id']);
                var_dump(['res' => $res]);
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

    public function info() {
        $user = $this->request->getAttribute('user');
        $where = [];
        $whereId = [];
        if ($user['project_id']) {
            $where[] = ['project_id', $user['project_id']];
            $whereId[] = ['id', $user['project_id']];
        }
        $result = parallel([
            function () use($where) {
                $res = Department::query()->where($where)->select('id', 'parent_id', 'name')->get();
                foreach ($res as $k => $v) {
                    $res[$k]['title'] = $v['name'];
                    $res[$k]['value'] = $v['id'];
                }
                return $this->common->treeNode($res->toArray());
            },
            function () use($where) {
                $res = Role::query()->where($where)->where('status', 80)->select('id', 'name', 'code')->get();
                foreach ($res as $k => $v) {
                    $res[$k]['label'] = $v['name'];
                    $res[$k]['value'] = $v['id'];
                }
                return $res;
            },
            function () use($whereId) {
                $res = Project::query()->where($whereId)->where('status', 80)->select('id', 'name')->get();
                foreach ($res as $k => $v) {
                    $res[$k]['label'] = $v['name'];
                    $res[$k]['value'] = $v['id'];
                }
                return $res;
            },
        ]);

        return $this->success([
            'department' => $result[0],
            'role' => $result[1],
            'project' => $result[2]
        ]);
    }

}