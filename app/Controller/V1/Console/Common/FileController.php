<?php


namespace App\Controller\V1\Console\Common;


use App\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\ConsoleMiddleware;

/**
 * Class FileController
 * @package App\Controller\V1\Console\Common
 * @AutoController()
 * @Middlewares({
 *     @Middleware(ConsoleMiddleware::class)
 * })
 */
class FileController extends AbstractController
{
    public function fetchOSSUploadToken() {
        return $this->success([
            'host' => 'https://www.mocky.io/v2/5cc8019d300000980a055e76',
            'accessId' => 'c2hhb2RhaG9uZw==',
            'policy' => 'eGl4aWhhaGFrdWt1ZGFkYQ==',
            'signature' => 'ZGFob25nc2hhbw=='
        ]);
    }

    public function upload() {
        return $this->success();
    }
}