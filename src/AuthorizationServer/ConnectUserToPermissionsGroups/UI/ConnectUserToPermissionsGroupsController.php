<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\UI;

use App\AuthorizationServer\ConnectUserToPermissionsGroups\Application\Command\ConnectUserToPermissionsGroupsCommand;
use App\AuthorizationServer\ConnectUserToPermissionsGroups\Application\ConnectUserToPermissionsGroupRequest;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\Json\JsonDecoderInterface;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class ConnectUserToPermissionsGroupsController extends ClientCredentialsAuthorizationController
{
    public function connectUsersToPermissionsGroups(
        Request $request,
        DenormalizerInterface $serializer,
        JsonDecoderInterface $jsonDecoder,
        MessageBusInterface $messageBus,
        IdentifierFactoryInterface $identifierFactory,
    ): JsonResponse {
        /** @var ConnectUserToPermissionsGroupRequest $connectUsersToPermissionsGroupsRequest */
        $connectUsersToPermissionsGroupsRequest = $serializer->denormalize(
            [
                'userId' => $request->get('userId'),
                'content' => $jsonDecoder->decode($request->getContent())->toArray(),
            ],
            ConnectUserToPermissionsGroupRequest::class,
        );

        try {
            $messageBus->dispatch(
                new ConnectUserToPermissionsGroupsCommand(
                    UserIdentifier::fromIdentifier(
                        $identifierFactory->fromString($connectUsersToPermissionsGroupsRequest->userId)
                    ),
                    $connectUsersToPermissionsGroupsRequest->permissionsGroupsIdsAsList(),
                )
            );

            return new JsonResponse(null, 204);
        } catch (CommandHandlerException $e) {
            return new JsonResponse(['data' => [], 'error' => $e->getMessage()], $e->getCode());
        }
    }
}
