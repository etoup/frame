<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\AppAuthException;
use App\Model\Log;
use App\Model\Permission;
use App\Model\Role;
use Donjan\Casbin\Enforcer;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qbhy\HyperfAuth\AuthMiddleware;
use Qbhy\HyperfAuth\Exception\AuthException;

class ConsoleMiddleware extends AuthMiddleware implements MiddlewareInterface
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
            //写入操作日志
            co(function () use ($request, $user) {
                $path = $request->getUri()->getPath();
                $host = $request->getUri()->getHost();
                $contents = $request->getBody()->getContents();
                $api = Permission::query()->where('path', $path)->first();
                $roles = Enforcer::getRolesForUser($user['id']);
                $role = Role::query()->whereIn('code', $roles)->pluck('name');
                if ($api) {
                    $mod = new Log();
                    $mod->user_id = $user['id'];
                    $mod->project_id = $user['project_id'];
                    $mod->department_id = $user['department_id'];
                    $mod->role = $role ? Json::encode($role): [];
                    $mod->host = $host;
                    $mod->title = $api['title'];
                    $mod->remark = $contents ?"{$path}:{$contents}": $path;
                    $mod->save();
                }
            });
            $request = $request->withAttribute('user', $user);
            return $handler->handle($request);
        } catch (AuthException $exception) {
            var_dump(['code' => $exception->getCode(), 'message' => $exception->getMessage()]);
            throw new AppAuthException();
        }
    }
}