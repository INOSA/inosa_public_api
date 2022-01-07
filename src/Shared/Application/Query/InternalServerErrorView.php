<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

use App\Shared\Domain\ResponseCode;

final class InternalServerErrorView implements ResponseViewInterface
{
    public function __construct(private string $response)
    {
    }

    public function getResponseContent(): string
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return ResponseCode::INTERNAL_SERVER_ERROR_CODE;
    }
}
