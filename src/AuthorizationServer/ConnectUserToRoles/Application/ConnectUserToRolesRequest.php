<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToRoles\Application;

use App\Shared\Domain\Identifier\RoleIdentifier;
use Inosa\Arrays\ArrayList;

final class ConnectUserToRolesRequest
{
    /**
     * @param string[] $roles
     */
    public function __construct(public readonly string $userId, public readonly array $roles)
    {
    }

    /**
     * @return ArrayList<RoleIdentifier>
     */
    public function rolesAsList(): ArrayList
    {
        return ArrayList::create($this->roles)->transform(
            fn(string $roleIdentifier): RoleIdentifier => new RoleIdentifier($roleIdentifier)
        );
    }
}
