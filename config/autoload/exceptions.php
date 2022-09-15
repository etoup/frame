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
return [
    'handler' => [
        'http' => [
            App\Exception\Handler\AppNotFoundHttpExceptionHandler::class,
            App\Exception\Handler\AppValidationExceptionHandler::class,
            App\Exception\Handler\AppExceptionHandler::class,
            App\Exception\Handler\AppAuthExceptionHandler::class,
            App\Exception\Handler\AppAuthorityExceptionHandle::class,
//            Qbhy\HyperfAuth\AuthExceptionHandler::class,
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
        ],
    ],
];
