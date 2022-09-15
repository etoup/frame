<?php


namespace App\Exception;

use App\Constants\CodeConstant;
use Hyperf\Server\Exception\ServerException;

class AppAuthorityException extends ServerException
{
    public function __construct(int $code = 403, string $message = null, \Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = CodeConstant::getMessage($code);
        }

        parent::__construct($message, $code, $previous);
    }
}