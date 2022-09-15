<?php


namespace App\Exception;


use App\Constants\CodeConstant;
use Qbhy\HyperfAuth\Exception\AuthException;

class AppAuthException extends AuthException
{
    public function __construct(int $code = 401, string $message = null, \Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = CodeConstant::getMessage($code);
        }

        parent::__construct($message, $code, $previous);
    }
}