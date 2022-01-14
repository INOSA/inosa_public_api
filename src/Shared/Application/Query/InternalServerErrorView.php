<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

final class InternalServerErrorView implements ResponseViewInterface
{
    public function __construct(
        private string $response,
        private int $statusCode,
    ) {
    }

    public function getResponseContent(): string
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
