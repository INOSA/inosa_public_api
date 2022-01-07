<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerUser\UI;

use App\AuthorizationServer\GetReadingStatusPerUser\Application\GetReadingStatusPerUserQueryInterface;
use App\AuthorizationServer\GetReadingStatusPerUser\Application\GetReadingStatusPerUserRequest;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class GetReadingStatusPerUserController extends ClientCredentialsAuthorizationController
{
    public function getReadingStatusPerUser(
        Request $request,
        ResponseFactory $responseFactory,
        GetReadingStatusPerUserQueryInterface $query,
        DenormalizerInterface $serializer,
    ): JsonResponse {
        $endpointRequest = $serializer->denormalize(
            $request->get('departmentId', []),
            GetReadingStatusPerUserRequest::class,
        );

        return $responseFactory->fromView($query->getReadingStatusPerUser($endpointRequest));
    }
}
