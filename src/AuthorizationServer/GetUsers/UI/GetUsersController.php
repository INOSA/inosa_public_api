<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetUsers\UI;

use App\AuthorizationServer\GetUsers\Application\GetUsersQueryInterface;
use App\AuthorizationServer\GetUsers\Application\GetUsersRequest;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class GetUsersController extends ClientCredentialsAuthorizationController
{
    public function getUsers(
        Request $request,
        GetUsersQueryInterface $query,
        ResponseFactory $responseFactory,
        DenormalizerInterface $serializer,
    ): JsonResponse {
        $request = $serializer->denormalize(
            $request->query->all(),
            GetUsersRequest::class,
        );

        return $responseFactory->fromView($query->getUsers($request));
    }
}
