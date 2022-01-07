<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoleStatusPerUser\UI;

use App\AuthorizationServer\GetRoleStatusPerUser\Application\Query\GetRoleStatusPerUserQueryInterface;
use App\AuthorizationServer\GetRoleStatusPerUser\Application\Query\GetRoleStatusPerUserRequest;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class GetRoleStatusPerUserController extends ClientCredentialsAuthorizationController
{
    public function getRoleStatusPerUser(
        Request $request,
        GetRoleStatusPerUserQueryInterface $query,
        ResponseFactory $responseFactory,
        DenormalizerInterface $denormalizer
    ): JsonResponse {
        try {
            $endpointRequest = $denormalizer->denormalize(
                $request->get('roleId', []),
                GetRoleStatusPerUserRequest::class,
            );

            return $responseFactory->fromView($query->getRoleStatusPerUserView($endpointRequest));
        } catch (ExceptionInterface $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
