<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetPermissionGroups\UI;

use App\AuthorizationServer\GetPermissionGroups\Application\GetPermissionGroupsQueryInterface;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetPermissionGroupsController extends ClientCredentialsAuthorizationController
{
    public function getPermissionGroups(GetPermissionGroupsQueryInterface $query, ResponseFactory $responseFactory): JsonResponse
    {
        return $responseFactory->fromView($query->getGetPermissionGroups());
    }
}
