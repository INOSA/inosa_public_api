<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\UI;

use App\AuthorizationServer\GetFoldersBasicStructure\Application\Query\GetFoldersBasicStructureQueryInterface;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetFoldersBasicStructureController extends ClientCredentialsAuthorizationController
{
    public function getFoldersBasicStructure(
        GetFoldersBasicStructureQueryInterface $query,
        ResponseFactory $responseFactory
    ): JsonResponse {
        return $responseFactory->fromView($query->getFolderBasicStructureView($this->getClient()->getInosaSiteId()));
    }
}
