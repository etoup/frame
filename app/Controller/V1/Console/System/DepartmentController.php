<?php


namespace App\Controller\V1\Console\System;


use App\Controller\AbstractController;
use App\Helper\Common;
use App\Model\Department;
use App\Model\Project;
use App\Request\V1\Console\System\Department\CreatedRequest;
use App\Request\V1\Console\System\Department\DeletedRequest;
use App\Request\V1\Console\System\Department\UpdatedRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\ConsoleMiddleware;

/**
 * Class DepartmentController
 * @package App\Controller\V1\Console\System
 * @AutoController()
 * @Middlewares({
 *     @Middleware(ConsoleMiddleware::class)
 * })
 */
class DepartmentController extends AbstractController
{
    /**
     * @var Common
     * @Inject()
     */
    private $common;

    public function list() {
        $user = $this->request->getAttribute('user');
        $where = [];
        if ($user['project_id']) {
            $where[] = ['department.project_id', $user['project_id']];
        }
        $result = parallel([
            function () use ($where) {
                $res = Department::query()
                    ->where($where)
                    ->leftJoin('project', 'project.id', '=', 'department.project_id')
                    ->select('department.*', 'project.name as project_name')
                    ->get();
                return $this->common->treeNode($res->toArray());
            },
            function () use ($where) {
                return Department::query()
                    ->where($where)
                    ->orderBy('id')
                    ->pluck('id');
            },
        ]);
        return [
            'data' => $result[0],
            'key' => $result[1],
            'success' => true
        ];
    }

    public function created(CreatedRequest $request){
        $user = $this->request->getAttribute('user');
        $data = $request->all();
        $data['project_id'] = $user['project_id'];
        $res = Department::query()->create($data);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function updated(UpdatedRequest $request) {
        $user = $this->request->getAttribute('user');
        $data = $request->all();
        $data['project_id'] = $user['project_id'];
        $res = Department::query()->where('id', $data['id'])->update($data);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function deleted(DeletedRequest $request) {
        $user = $this->request->getAttribute('user');
        $data = $request->all();
        if ($user['project_id'])
            $res = Department::query()->where('project_id', $user['project_id'])->where('id', $data['id'])->delete();
        else
            $res = Department::query()->where('id', $data['id'])->delete();
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function project() {
        $user = $this->request->getAttribute('user');
        if ($user['project_id'])
            $list = Project::query()->where('id', $user['project_id'])->where('status', 80)->get();
        else
            $list = Project::query()->where('status', 80)->get();
        return $this->success($list);
    }
}