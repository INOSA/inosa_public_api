<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\Application\Command;

use App\Shared\Application\MessageBus\SyncCommandInterface;
use App\Shared\Domain\Identifier\PermissionGroupsIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use Inosa\Arrays\ArrayList;

final class ConnectUsersToPermissionsGroupsCommand implements SyncCommandInterface
{
    /**
     * @param ArrayList<PermissionGroupsIdentifier> $permissionsGroupsIds
     */
    public function __construct(
        public readonly UserIdentifier $userId,
        public readonly ArrayList $permissionsGroupsIds,
    ) {
    }
}
