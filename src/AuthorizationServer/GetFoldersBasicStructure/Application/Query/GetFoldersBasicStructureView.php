<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Application\Query;

use App\Shared\Application\Query\ResponseViewInterface;

final class GetFoldersBasicStructureView implements ResponseViewInterface
{
    public function __construct(private ResponseViewInterface $responseView)
    {
    }

    public function getResponse(): string
    {
        return $this->responseView->getResponse();
    }

    public function getStatusCode(): int
    {
        return $this->responseView->getStatusCode();
    }
}
