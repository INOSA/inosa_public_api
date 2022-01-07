<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDepartments\UI;

use App\AuthorizationServer\GetDepartments\Application\GetDepartmentsQueryInterface;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetDepartmentsController extends ClientCredentialsAuthorizationController
{
    public function getDepartments(
        GetDepartmentsQueryInterface $query,
        ResponseFactory $responseFactory,
    ): JsonResponse {
        $response = $query->getDepartments($this->getClient()->getInosaSiteId());

        return $responseFactory->fromView($response);
    }
}
