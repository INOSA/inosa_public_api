<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

final class InternalServerErrorView implements ResponseViewInterface
{
    private const INTERNAL_SERVER_ERROR_STATUS_CODE = 500;

    public function __construct(private string $response)
    {
    }

    public function getResponseContent(): string
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return self::INTERNAL_SERVER_ERROR_STATUS_CODE;
    }
}
