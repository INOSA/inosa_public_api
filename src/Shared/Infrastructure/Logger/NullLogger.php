<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Logger;

use Symfony\Contracts\HttpClient\ResponseInterface;

final class NullLogger implements RequestLoggerInterface
{
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    public function logRequest(ResponseInterface $response): void
    {
    }
}
