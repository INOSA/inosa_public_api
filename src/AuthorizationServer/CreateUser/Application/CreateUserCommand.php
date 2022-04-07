<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Application;

use App\AuthorizationServer\CreateUser\Domain\User\Email;
use App\AuthorizationServer\CreateUser\Domain\User\FirstName;
use App\AuthorizationServer\CreateUser\Domain\User\LastName;
use App\AuthorizationServer\CreateUser\Domain\User\UserName;
use App\Shared\Application\MessageBus\SyncCommandInterface;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use App\Shared\Domain\Identifier\PermissionGroupsIdentifier;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use Inosa\Arrays\ArrayList;

final class CreateUserCommand implements SyncCommandInterface
{
    /**
     * @param ArrayList<PermissionGroupsIdentifier> $permissionsGroups
     * @param ArrayList<RoleIdentifier> $roles
     */
    public function __construct(
        public readonly UserIdentifier $userIdentifier,
        public readonly UserName $userName,
        public readonly FirstName $firstName,
        public readonly LastName $lastName,
        public readonly Email $email,
        public readonly ArrayList $permissionsGroups,
        public readonly DepartmentIdentifier $departmentIdentifier,
        public readonly ArrayList $roles
    ) {
    }
}
