<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

abstract class AbstractController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @Inject
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @Inject
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @Inject
     * @var LoggerFactory
     */
    protected $logger;

    /**
     * @param null $data
     * @param string $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function success($data = null, string $message = '操作成功'): \Psr\Http\Message\ResponseInterface
    {
        $items = [
            'code' => 200,
            'status' => 'success',
            'message' => $message
        ];
        isset($data) && $items['data'] = $data;
        return $this->response->json($items);
    }

    /**
     * @param string $message
     * @param int $code
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function fail(string $message = '操作失败', int $code = 200, $data = null): \Psr\Http\Message\ResponseInterface
    {
        $items = [
            'code' => $code,
            'status' => 'fail',
            'message' => $message,
        ];
        isset($data) && $items['data'] = $data;
        return $this->response->json($items);
    }
}
