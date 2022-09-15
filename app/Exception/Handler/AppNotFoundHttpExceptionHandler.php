<?php


namespace App\Exception\Handler;

use Hyperf\HttpMessage\Exception\NotFoundHttpException;
use App\Helper\Common;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppNotFoundHttpExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @var Common
     * @Inject
     */
    private $common;

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 判断被捕获到的异常是希望被捕获的异常
        if ($throwable instanceof NotFoundHttpException) {
            $this->stopPropagation();
            $data = $this->common->fail($throwable->getMessage(), $throwable->getCode());
            return $response->withAddedHeader('Content-Type', 'application/json; charset=utf-8')->withStatus($throwable->getCode())->withBody(new SwooleStream(
                Json::encode($data)
            ));
        }
        // 交给下一个异常处理器
        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}