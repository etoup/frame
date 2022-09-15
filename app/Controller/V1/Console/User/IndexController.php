<?php


namespace App\Controller\V1\Console\User;

use App\Controller\AbstractController;
use App\Helper\Common;
use App\Model\Permission;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\ConsoleMiddleware;
use Illuminate\Hashing\BcryptHasher;
use Donjan\Casbin\Enforcer;

/**
 * Class IndexController
 * @package App\Controller\V1\Console\User
 * @AutoController()
 * @Middlewares({
 *     @Middleware(ConsoleMiddleware::class)
 * })
 */
class IndexController extends AbstractController
{
    /**
     * @var Common
     * @Inject
     */
    private $common;

    /**
     * @var BcryptHasher
     * @Inject
     */
    private $hash;

    public function index() {
        $user = $this->request->getAttribute('user');
        $data = [
            'menus' => [],
            'permissions' => []
        ];
        if ($user['super'] !== 80) {
            $permissions = Enforcer::getImplicitPermissionsForUser($user['id']);
            foreach ($permissions as $k => $v) {
                $permission = Permission::query()->find($v[1]);
                $permission['key'] = (string)$permission['id'];
                $data['menus'][] = $permission;
                if ($permission['type'] === 20) {
                    $data['permissions'][] = $permission;
                }
            }
        }
        $result = parallel([
            function() use($user, $data) {
                switch ($user['super']) {
                    case 80:
                        $res = Permission::query()->where('type', 10)->where('status', 80)->orderBy('sort')->get();
                        break;
                    default:
                        $list = $data['menus'];
                        $items = $this->getItems();
                        $ids = [];
                        foreach ($list as $v) {
                            array_push($ids, $v['id']);
                            $arr = $this->getParentID($items, $v['id']);
                            foreach ($arr as $i) {
                                if (!in_array($i, $ids)){
                                    array_push($ids, $i);
                                }
                            }
                        }
                        $res = Permission::query()->whereIn('id', $ids)->where('type', 10)->orderBy('sort')->get();
                }
                return $this->common->treeNode($res->toArray());
            },
            function() use($user, $data) {
                return $data['permissions'];
            }
        ]);
        return $this->success([
            'name' => $user['username'] ? $this->common->mobileLastNumber($user['username']) : '',
            'avatar' => $user['avatar_url'] ?: 'https://gw.alipayobjects.com/zos/antfincdn/XAosXuNZyF/BiazfanxmamNRoxxVxka.png',
            'super' => $user['super'] === 80 ? 'super' : 'user',
            'menus' => $result[0],
            'permissions' => $result[1],
        ]);
    }

    private function getItems() {
        $res = Permission::query()->where('status', 80)->get();
        $list = $res->toArray();
        $items = [];
        foreach ($list as $v) {
            $items[$v['id']] = $v['parent_id'];
        }
        return $items;
    }

    private function getParentID($items, $id, &$arr = []) {
        $pid = $items[$id];
        if ($pid != 0) {
            array_push($arr, $pid);
            $this->getParentID($items, $pid, $arr);
        }
        return $arr;
    }

}