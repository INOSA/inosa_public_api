<?php

declare(strict_types=1);

namespace App\AuthorizationServer\DeleteUser\UI;

use App\AuthorizationServer\DeleteUser\Application\Command\DeleteUserCommandFactory;
use App\AuthorizationServer\DeleteUser\Application\DeleteUserRequest;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class DeleteUserController extends ClientCredentialsAuthorizationController
{
    public function deleteUser(
        string $userId,
        Request $request,
        MessageBusInterface $messageBus,
        DenormalizerInterface $serializer,
        DeleteUserCommandFactory $deleteUserCommandFactory,
    ): JsonResponse {
        /** @var DeleteUserRequest $deleteUserRequest */
        $deleteUserRequest = $serializer->denormalize(
            array_merge(
                $request->query->all(),
                ['userId' => $userId],
            ),
            DeleteUserRequest::class,
        );

        try {
            $messageBus->dispatch($deleteUserCommandFactory->create($deleteUserRequest));

            return new JsonResponse(null, 204);
        } catch (CommandHandlerException $e) {
            return new JsonResponse(['data' => [], 'error' => $e->getMessage()], $e->getCode());
        }
    }
}
