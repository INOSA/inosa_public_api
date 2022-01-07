<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToRoles\Application\Command;

use App\AuthorizationServer\ConnectUserToRoles\Domain\ConnectUserToRolesApi;
use App\AuthorizationServer\ConnectUserToRoles\Domain\ConnectUserToRolesEndpoint;
use App\AuthorizationServer\ConnectUserToRoles\Domain\ConnectUserToRolesException;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\MessageBus\CommandHandlerInterface;

final class ConnectUserToRolesHandler implements CommandHandlerInterface
{
    public function __construct(private ConnectUserToRolesApi $connectUserToRolesApi)
    {
    }

    public function __invoke(ConnectUserToRolesCommand $command): void
    {
        try {
            $this->connectUserToRolesApi->request(
                new ConnectUserToRolesEndpoint($command->userIdentifier, $command->roles)
            );
        } catch (ConnectUserToRolesException $e) {
            throw new CommandHandlerException(
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
