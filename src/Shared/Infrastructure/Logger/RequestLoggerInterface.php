<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Logger;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface RequestLoggerInterface
{
    public function logRequest(ResponseInterface $response): void;
}
