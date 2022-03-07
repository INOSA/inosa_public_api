<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use RuntimeException;

abstract class ProxyResponseException extends RuntimeException
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }
}
