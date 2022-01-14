<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerDepartment\UI;

use App\AuthorizationServer\GetReadingStatusPerDepartment\Infrastructure\GetReadingStatusPerDepartmentQuery;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetReadingStatusPerDepartmentController extends ClientCredentialsAuthorizationController
{
    public function getReadingStatusPerDepartment(
        GetReadingStatusPerDepartmentQuery $query,
        ResponseFactory $responseFactory
    ): JsonResponse {
        return $responseFactory->fromView(
            $query->getReadingStatusPerDepartmentView($this->getClient()->getInosaSiteId())
        );
    }
}
