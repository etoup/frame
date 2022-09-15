<?php


namespace App\Controller\V1\Api\User;


use App\Controller\AbstractController;
use App\Factory\File\QiniuUploadFactory;
use App\Middleware\UserMiddleware;
use App\Model\UserPropose;
use App\Request\V1\Api\User\Index\CreatedRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * Class IndexController
 * @package App\Controller\V1\Api\User
 * @AutoController()
 * @Middleware(UserMiddleware::class)
 */
class IndexController extends AbstractController
{
    /**
     * @var QiniuUploadFactory
     * @Inject()
     */
    private $upload;

    public function index() {
        $config = config('common.default');
        return $this->success($config);
    }

    public function qiniuToken() {
        $key = $this->request->input('key', null);
        $token = $this->upload->getToken($key);
        if (!$token) {
            return $this->fail();
        }
        return $this->success($token);
    }
}