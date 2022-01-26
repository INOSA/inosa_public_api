<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoles\Application\Query;

use App\Shared\Application\Query\ResponseViewInterface;

final class GetRolesView implements ResponseViewInterface
{
    public function __construct(private ResponseViewInterface $responseView)
    {
    }

    public function getResponseContent(): string
    {
        return $this->responseView->getResponseContent();
    }

    public function getStatusCode(): int
    {
        return $this->responseView->getStatusCode();
    }
}
