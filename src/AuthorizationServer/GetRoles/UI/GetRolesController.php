<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoles\UI;

use App\AuthorizationServer\GetRoles\Application\Query\GetRolesQueryInterface;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetRolesController extends ClientCredentialsAuthorizationController
{
    public function getRoles(
        GetRolesQueryInterface $query,
        ResponseFactory $responseFactory
    ): JsonResponse {
        return $responseFactory->fromView($query->getRoles());
    }
}
