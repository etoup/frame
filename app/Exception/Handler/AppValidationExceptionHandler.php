<?php


namespace App\Exception\Handler;

use App\Helper\Common;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Validation\ValidationException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppValidationExceptionHandler extends ExceptionHandler
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
        if ($throwable instanceof ValidationException) {
            $this->stopPropagation();
            $data = $this->common->fail($throwable->validator->errors()->first(), $throwable->getCode());
            return $response->withAddedHeader('Content-Type', 'application/json; charset=utf-8')->withStatus($throwable->getCode())->withBody(new SwooleStream(
                Json::encode($data)
            ));
        }
        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}