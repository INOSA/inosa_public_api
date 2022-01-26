<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoleStatusPerUser\Application\Query;

use App\Shared\Domain\Identifier\RoleIdentifier;
use Inosa\Arrays\ArrayList;

final class GetRoleStatusPerUserRequest
{
    /**
     * @param ArrayList<RoleIdentifier> $roleIds
     */
    public function __construct(private ArrayList $roleIds)
    {
    }

    /**
     * @return ArrayList<RoleIdentifier>
     */
    public function getRoleIds(): ArrayList
    {
        return $this->roleIds;
    }
}
