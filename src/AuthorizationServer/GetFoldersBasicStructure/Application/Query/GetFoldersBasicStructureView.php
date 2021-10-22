<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Application\Query;

use App\Shared\Application\Query\ResponseViewInterface;

final class GetFoldersBasicStructureView implements ResponseViewInterface
{
    public function __construct(
        private string $response,
        private int $statusCode,
    ) {
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
