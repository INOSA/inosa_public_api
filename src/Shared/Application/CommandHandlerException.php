<?php

declare(strict_types=1);

namespace App\Shared\Application;

use RuntimeException;

final class CommandHandlerException extends RuntimeException
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }
}
