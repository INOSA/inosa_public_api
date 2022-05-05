<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\Application\Command;

use App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain\ConnectUserToPermissionsGroupsApi;
use App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain\ConnectUserToPermissionsGroupsEndpoint;
use App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain\ConnectUserToPermissionsGroupsException;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\MessageBus\CommandHandlerInterface;

final class ConnectUserToPermissionsGroupsHandler implements CommandHandlerInterface
{
    public function __construct(private readonly ConnectUserToPermissionsGroupsApi $connectUsersToPermissionsGroupsApi)
    {
    }

    public function __invoke(ConnectUserToPermissionsGroupsCommand $command): void
    {
        $connectUsersToPermissionsGroupsEndpoint = new ConnectUserToPermissionsGroupsEndpoint(
            $command->userId,
            $command->permissionsGroupsIds,
        );

        try {
            $this->connectUsersToPermissionsGroupsApi->request($connectUsersToPermissionsGroupsEndpoint);
        } catch (ConnectUserToPermissionsGroupsException $e) {
            throw new CommandHandlerException(
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }
}
