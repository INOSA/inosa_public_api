<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDepartments\Application;

use App\Shared\Application\Query\ResponseViewInterface;

final class GetDepartmentsView implements ResponseViewInterface
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
