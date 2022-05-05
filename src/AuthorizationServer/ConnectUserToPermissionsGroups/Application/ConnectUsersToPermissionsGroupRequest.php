<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\Application;

use App\Shared\Domain\Identifier\PermissionGroupsIdentifier;
use Inosa\Arrays\ArrayList;

final class ConnectUsersToPermissionsGroupRequest
{
    /**
     * @param string[] $permissionsGroupsIds
     */
    public function __construct(public readonly string $userId, public readonly array $permissionsGroupsIds)
    {
    }

    /**
     * @return ArrayList<PermissionGroupsIdentifier>
     */
    public function permissionsGroupsIdsAsList(): ArrayList
    {
        return ArrayList::create($this->permissionsGroupsIds)->transform(
            fn(string $permissionsGroupsIdentifier): PermissionGroupsIdentifier => new PermissionGroupsIdentifier(
                $permissionsGroupsIdentifier
            )
        );
    }
}
