<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToRoles\UI;

use App\AuthorizationServer\ConnectUserToRoles\Application\Command\ConnectUserToRolesCommand;
use App\AuthorizationServer\ConnectUserToRoles\Application\ConnectUserToRolesRequest;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\Json\JsonDecoderInterface;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class ConnectUserToRolesController extends ClientCredentialsAuthorizationController
{
    public function connectUserToRoles(
        Request $request,
        DenormalizerInterface $serializer,
        JsonDecoderInterface $jsonDecoder,
        MessageBusInterface $messageBus,
        IdentifierFactoryInterface $identifierFactory,
    ): JsonResponse {
        /** @var ConnectUserToRolesRequest $connectUserToRolesRequest */
        $connectUserToRolesRequest = $serializer->denormalize(
            [
                'userId' => $request->get('userId'),
                'content' => $jsonDecoder->decode($request->getContent())->toArray(),
            ],
            ConnectUserToRolesRequest::class,
        );

        try {
            $messageBus->dispatch(
                new ConnectUserToRolesCommand(
                    UserIdentifier::fromIdentifier($identifierFactory->fromString($connectUserToRolesRequest->userId)),
                    $connectUserToRolesRequest->rolesAsList(),
                )
            );

            return new JsonResponse(null, 204);
        } catch (CommandHandlerException $e) {
            return new JsonResponse(['data' => [], 'error' => $e->getMessage()], $e->getCode());
        }
    }
}
