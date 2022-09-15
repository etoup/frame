<?php

declare(strict_types=1);

namespace App\Controller\V1\Console\User;

use App\Controller\AbstractController;
use App\Helper\Common;
use App\Model\User;
use App\Request\V1\Console\User\Guard\CodeRequest;
use App\Request\V1\Console\User\Guard\LoginRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Qbhy\HyperfAuth\AuthManager;
use Illuminate\Hashing\BcryptHasher;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class GuardController.
 * @AutoController
 */
class GuardController extends AbstractController
{
    /**
     * @var BcryptHasher
     * @Inject
     */
    private $hash;

    /**
     * @var AuthManager
     * @Inject
     */
    protected $auth;

    /**
     * @var Common
     * @Inject
     */
    private $common;

    public function index()
    {
        $mobile = $this->request->input('mobile');
        if (! $mobile) {
            return $this->fail();
        }
        try {
            $code = $this->cache->get($mobile);
            return $this->success($code);
        } catch (InvalidArgumentException $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function code(CodeRequest $request)
    {
        $data = $request->all();
        try {
            $code = '123456';
//            TODO 发送验证码
//            $code = $this->common->generateNumber();
            if (isset($data['has_mobile'])) {
                $user = User::query()->where('mobile', $data['mobile'])->count();
                if (!$user) {
                    return $this->fail('手机号码错误');
                }
            }
            $res = $this->cache->set($data['mobile'], $code, 60);
            if (! $res) {
                return $this->fail();
            }
            return $this->success();
        } catch (InvalidArgumentException $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::query()->where('username', $data['username'])->first();
        if (! $user) {
            return $this->fail('用户不存在');
        }
        try {
            if (!$this->hash->check($data['password'], $user['password'])) {
                return $this->fail('用户名或密码错误');
            }
            if ($user['status'] != 80) {
                return $this->fail('账号被冻结');
            }

            $token = $this->auth->login($user);
            return $this->success([
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'avatar_url' => $user['avatar_url'],
                    'mobile' => $user['mobile'],
                ],
                'token' => $token,
                'currentAuthority' => 'admin',
                'type' => 'account'
            ]);
        } catch (InvalidArgumentException $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function logout()
    {
        try {
            $res = $this->auth->logout();
            if (! $res) {
                return $this->fail();
            }
            return $this->success();
        } catch (InvalidArgumentException $e) {
        }
    }
}