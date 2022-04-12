<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\UI;

use App\AuthorizationServer\CreateUser\Application\CreateUserCommand;
use App\AuthorizationServer\CreateUser\Application\CreateUserRequest;
use App\AuthorizationServer\CreateUser\Domain\User\Email;
use App\AuthorizationServer\CreateUser\Domain\User\FirstName;
use App\AuthorizationServer\CreateUser\Domain\User\LastName;
use App\AuthorizationServer\CreateUser\Domain\User\UserName;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\Json\JsonDecoderInterface;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class CreateUserController extends ClientCredentialsAuthorizationController
{
    public function createUser(
        Request $request,
        JsonDecoderInterface $decoder,
        MessageBusInterface $messageBus,
        DenormalizerInterface $serializer,
        IdentifierFactoryInterface $identifierFactory,
    ): JsonResponse {
        /** @var CreateUserRequest $createUserRequest */
        $createUserRequest = $serializer->denormalize(
            $decoder->decode($request->getContent())->toArray(),
            CreateUserRequest::class,
        );

        try {
            $command = new CreateUserCommand(
                UserIdentifier::fromIdentifier($identifierFactory->fromString($createUserRequest->userIdentifier)),
                new UserName($createUserRequest->userName),
                new FirstName($createUserRequest->firstName),
                new LastName($createUserRequest->lastName),
                new Email($createUserRequest->email),
                $createUserRequest->permissionsGroupsAsList(),
                new DepartmentIdentifier($createUserRequest->departmentIdentifier),
                $createUserRequest->rolesAsList(),
            );

            $messageBus->dispatch($command);

            return new JsonResponse([], 201);
        } catch (CommandHandlerException $e) {
            return new JsonResponse(['data' => [], 'error' => $e->getMessage()], $e->getCode());
        }
    }
}
