<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Application;

use App\AuthorizationServer\CreateUser\Domain\CreateUserApi;
use App\AuthorizationServer\CreateUser\Domain\CreateUserEndpoint;
use App\AuthorizationServer\CreateUser\Domain\CreateUserException;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\MessageBus\CommandHandlerInterface;

final class CreateUserHandler implements CommandHandlerInterface
{
    public function __construct(private CreateUserApi $createUserApi)
    {
    }

    public function __invoke(CreateUserCommand $createUserCommand): void
    {
        $endpoint = new CreateUserEndpoint(
            $createUserCommand->userIdentifier,
            $createUserCommand->userName,
            $createUserCommand->firstName,
            $createUserCommand->lastName,
            $createUserCommand->email,
            $createUserCommand->permissionsGroups,
            $createUserCommand->departmentIdentifier,
            $createUserCommand->roles,
        );

        try {
            $this->createUserApi->request($endpoint);
        } catch (CreateUserException $e) {
            throw new CommandHandlerException(
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
