<?php


namespace App\Controller\V1\Api\Common;

use App\Controller\AbstractController;
use App\Factory\Weapp\WeChatFactory;
use App\Model\User;
use App\Request\V1\Api\Common\Index\TokenRequest;
use Carbon\Carbon;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Qbhy\HyperfAuth\AuthManager;

/**
 * Class IndexController
 * @package App\Controller\V1\Api\Common
 * @AutoController()
 */
class IndexController extends AbstractController
{
    /**
     * @var AuthManager
     * @Inject
     */
    protected $auth;

    /**
     * @var WeChatFactory
     * @Inject
     */
    private $wechat;

    public function index() {
        return $this->success();
    }

    /**
     * @param TokenRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function token(TokenRequest $request) {
        try {
            $data = $request->all();
            $app = $this->wechat->create();
            $auth = $app->auth->session($data['code']);
            if (!$auth) {
                return $this->fail('缓存错误');
            }
            if (!isset($auth['openid'])) {
                return $this->fail('授权信息错误');
            }
            $user = User::query()->where('open_id', $auth['openid'])->first();
            if (!$user) {
                $mod = new User();
                $mod->open_id = $auth['openid'];
                $mod->project_id = 2;
                $mod->department_id = 4;
                isset($auth['unionid']) && $mod->union_id = $auth['unionid'];
                $res = $mod->save();
                if (!$res) {
                    return $this->fail('登录失败');
                }
                $user = User::query()->where('id', $mod->getKey())->where('open_id', $auth['openid'])->first();
            }
            $token = $this->auth->login($user);
            return $this->success([
                'token' => $token,
                'expire_at' => (Carbon::now()->addMinutes(60 * 60)->timestamp) * 1000
            ]);
        } catch (InvalidConfigException $e) {
            return $this->fail('登录失败');
        } catch (InvalidArgumentException $e) {
            return $this->fail('登录失败');
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }

    public function upload() {
        $file = $this->request->file('file');
        var_dump($file);
        return $this->success();
    }
}