<?php


namespace App\Controller\V1\Console\Project;


use App\Controller\AbstractController;
use App\Helper\Common;
use App\Model\Project;
use App\Request\V1\Console\Project\Index\CreatedRequest;
use App\Request\V1\Console\Project\Index\FreezeRequest;
use App\Request\V1\Console\Project\Index\UpdatedRequest;
use Carbon\Carbon;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\ConsoleMiddleware;
use App\Middleware\AuthorityMiddleware;

/**
 * Class IndexController
 * @package App\Controller\V1\Console\Project
 * @AutoController()
 * @Middlewares({
 *     @Middleware(ConsoleMiddleware::class),
 *     @Middleware(AuthorityMiddleware::class)
 * })
 */
class IndexController extends AbstractController
{
    /**
     * @var Common
     * @Inject()
     */
    protected $common;

    public function list() {
        $request = $this->request->all();
        $current = isset($request['current']) ? (int)$request['current'] : 1;
        $pageSize = isset($request['pageSize']) ? (int)$request['pageSize'] : 10;
        $sorter = $request['sorter'] ?? 'created_at_descend';
        $where = [];
        isset($request['name']) && $where[] = ['name', 'like', "%{$request['name']}%"];
        isset($request['path']) && $where[] = ['path', 'like', "%{$request['path']}%"];
        isset($request['description']) && $where[] = ['description', 'like', "%{$request['description']}%"];
        isset($request['status']) && $where[] = ['status', $request['status']];
        isset($request['startTime']) && $where[] = ['created_at', '>', $request['startTime']];
        isset($request['endTime']) && $where[] = ['created_at', '<', $request['endTime']];
        $result = parallel([
            function () use ($sorter, $where, $current, $pageSize) {
                switch ($sorter) {
                    case 'created_at_descend':
                        $list = Project::query()
                            ->where($where)
                            ->forPage($current, $pageSize)
                            ->orderByDesc('created_at')
                            ->get();
                        break;
                    default:
                        $list = Project::query()
                            ->where($where)
                            ->forPage($current, $pageSize)
                            ->orderBy('created_at')
                            ->get();
                }
                return $list ?: [];
            },
            function () use ($where) {
                $total = Project::query()->where($where)->count();
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
        if ($data['path']) {
            if (!$this->common->check_url($data['path'])) {
                return $this->fail('请填写正确的地址');
            }
        }
        switch($data['duration']) {
            case '0':
                break;
            default:
                $data['expire_at'] = Carbon::now()->addDays((int)$data['duration']);
        }
        $res = Project::query()->create($data);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function updated(UpdatedRequest $request) {
        $data = $request->all();
        if ($data['path']) {
            if (!$this->common->check_url($data['path'])) {
                return $this->fail('请填写正确的地址');
            }
        }
        $project = Project::query()->where('id', $data['id'])->first();
        if (isset($data['duration'])) {
            switch($data['duration']) {
                case '0':
                    $data['expire_at'] = null;
                    break;
                default:
                    $data['expire_at'] = Carbon::parse($project['expire_at'])->addDays((int)$data['duration']);
            }
        }
        $res = $project->update($data);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function freeze(FreezeRequest $request) {
        $data = $request->all();
        $res = is_array($data['key']) ?
            Project::query()->whereIn('id', $data['key'])->update(['status' => 40, 'remark' => $data['remark'] ?? '']) :
            Project::query()->where('id', $data['key'])->update(['status' => 40, 'remark' => $data['remark'] ?? '']);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }

    public function unfreeze(FreezeRequest $request) {
        $data = $request->all();
        $res = is_array($data['key']) ?
            Project::query()->whereIn('id', $data['key'])->update(['status' => 80, 'remark' => $data['remark'] ?? '']) :
            Project::query()->where('id', $data['key'])->update(['status' => 80, 'remark' => $data['remark'] ?? '']);
        if (!$res) {
            return $this->fail();
        }
        return $this->success();
    }
}