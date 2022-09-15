<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\AppAuthException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qbhy\HyperfAuth\AuthMiddleware;
use Qbhy\HyperfAuth\Exception\AuthException;

class UserMiddleware extends AuthMiddleware
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $user = $this->auth->user();
            if (!$user) {
                throw new AppAuthException();
            }
            $request = $request->withAttribute('user', $user);
            return $handler->handle($request);
        } catch (AuthException $exception){
            var_dump(['code' => $exception->getCode(), 'message' => $exception->getMessage()]);
            throw new AppAuthException();
        }
    }
}