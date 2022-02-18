<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusForOneUser\UI;

use App\AuthorizationServer\GetReadingStatusForOneUser\Application\Query\GetReadingStatusForOneUserQueryInterface;
use App\AuthorizationServer\GetReadingStatusForOneUser\Application\Query\GetReadingStatusForOneUserRequest;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class GetReadingStatusForOneUserController extends ClientCredentialsAuthorizationController
{
    public function getReadingStatusForOneUser(
        Request $request,
        GetReadingStatusForOneUserQueryInterface $query,
        ResponseFactory $responseFactory,
        DenormalizerInterface $serializer
    ): JsonResponse {
        try {
            $endpointRequest = $serializer->denormalize(
                [
                    'userId' => $request->get('id'),
                    'roleIds' => $request->get('roleId', []),
                    'documentIds' => $request->get('documentId', []),
                ],
                GetReadingStatusForOneUserRequest::class,
            );

            return $responseFactory->fromView($query->getReadingStatusForOneUserView($endpointRequest));
        } catch (ExceptionInterface $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
