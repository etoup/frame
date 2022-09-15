<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\CodeConstant;
use App\Exception\AppAuthException;
use App\Exception\AppAuthorityException;
use App\Model\Permission;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Donjan\Casbin\Enforcer;

class AuthorityMiddleware implements MiddlewareInterface
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
        $user = $request->getAttribute('user');
        if (!$user) {
            throw new AppAuthException(CodeConstant::UNAUTHENTICATED, '请先登录');
        }
        switch ($user['super']) {
            case 80:
                $request = $request->withAttribute('user', $user);
                return $handler->handle($request);
                break;
            default:
                $path = $request->getUri()->getPath();
                $permissionId = Permission::query()->where('path', $path)->value('id');
                if (!$permissionId) {
                    throw new AppAuthorityException(CodeConstant::DISALLOW, '权限不够');
                }
                $res = Enforcer::enforce((string)$user['id'], (string)$permissionId, $path);
                if ($res) {
                    $request = $request->withAttribute('user', $user);
                    return $handler->handle($request);
                }
                throw new AppAuthorityException(CodeConstant::DISALLOW, '权限不够');
        }
    }
}