<?php

declare(strict_types=1);

namespace App\AuthorizationServer\DeleteUser\Application\Command;

use App\AuthorizationServer\DeleteUser\Domain\DeleteUserApi;
use App\AuthorizationServer\DeleteUser\Domain\DeleteUserEndpoint;
use App\AuthorizationServer\DeleteUser\Domain\DeleteUserException;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\MessageBus\CommandHandlerInterface;

final class DeleteUserHandler implements CommandHandlerInterface
{
    public function __construct(private readonly DeleteUserApi $api)
    {
    }

    public function __invoke(DeleteUserCommand $command): void
    {
        try {
            $this->api->request(
                new DeleteUserEndpoint(
                    $command->userToDeleteId,
                    $command->substituteItemsResponsibleId,
                    $command->substituteCoSignerId,
                )
            );
        } catch (DeleteUserException $e) {
            throw new CommandHandlerException(
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }
}
