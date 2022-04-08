<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Application;

use App\Shared\Domain\Identifier\PermissionGroupsIdentifier;
use App\Shared\Domain\Identifier\RoleIdentifier;
use Inosa\Arrays\ArrayList;

final class CreateUserRequest
{
    /**
     * @param array<string> $permissionsGroups
     * @param array<string> $roles
     */
    public function __construct(
        public readonly string $userIdentifier,
        public readonly string $userName,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly array $permissionsGroups,
        public readonly string $departmentIdentifier,
        public readonly array $roles
    ) {
    }

    /**
     * @return ArrayList<PermissionGroupsIdentifier>
     */
    public function permissionsGroupsAsList(): ArrayList
    {
        return ArrayList::create($this->permissionsGroups)
            ->transform(
                fn(string $permissionGroupsIdentifier): PermissionGroupsIdentifier => new PermissionGroupsIdentifier(
                    $permissionGroupsIdentifier
                )
            );
    }

    /**
     * @return ArrayList<RoleIdentifier>
     */
    public function rolesAsList(): ArrayList
    {
        return ArrayList::create($this->roles)
            ->transform(
                fn(string $roleIdentifier): RoleIdentifier => new RoleIdentifier($roleIdentifier)
            );
    }
}
