<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\Application\Command;

use App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain\ConnectUsersToPermissionsGroupsApi;
use App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain\ConnectUsersToPermissionsGroupsEndpoint;
use App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain\ConnectUsersToPermissionsGroupsException;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\MessageBus\CommandHandlerInterface;

final class ConnectUsersToPermissionsGroupsHandler implements CommandHandlerInterface
{
    public function __construct(private readonly ConnectUsersToPermissionsGroupsApi $connectUsersToPermissionsGroupsApi)
    {
    }

    public function __invoke(ConnectUsersToPermissionsGroupsCommand $command): void
    {
        $connectUsersToPermissionsGroupsEndpoint = new ConnectUsersToPermissionsGroupsEndpoint(
            $command->userId,
            $command->permissionsGroupsIds,
        );

        try {
            $this->connectUsersToPermissionsGroupsApi->request($connectUsersToPermissionsGroupsEndpoint);
        } catch (ConnectUsersToPermissionsGroupsException $e) {
            throw new CommandHandlerException(
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }
}
