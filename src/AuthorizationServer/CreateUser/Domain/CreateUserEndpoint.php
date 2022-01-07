<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Domain;

use App\AuthorizationServer\CreateUser\Domain\User\Email;
use App\AuthorizationServer\CreateUser\Domain\User\FirstName;
use App\AuthorizationServer\CreateUser\Domain\User\LastName;
use App\AuthorizationServer\CreateUser\Domain\User\UserName;
use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use App\Shared\Domain\Identifier\PermissionGroupsIdentifier;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;

final class CreateUserEndpoint implements PostEndpointInterface
{
    private const URL = 'users/inosa';

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

    public function getUrl(): Url
    {
        return new Url(self::URL);
    }

    /**
     * @inheritDoc
     */
    public function getParams(): ArrayHashMap
    {
        return ArrayHashMap::create(
            [
                'id' => $this->userIdentifier->toString(),
                'userName' => $this->userName->toString(),
                'firstName' => $this->firstName->toString(),
                'lastName' => $this->lastName->toString(),
                'email' => $this->email->toString(),
                'permissionsGroupsIds' => $this->transformPermissionGroupsIdentifiersToString()->toArray(),
                'departmentId' => $this->departmentIdentifier->toString(),
                'rolesIds' => $this->transformRoleIdentifiersToString()->toArray(),
            ]
        );
    }

    /**
     * @return ArrayList<string>
     */
    private function transformRoleIdentifiersToString(): ArrayList
    {
        return $this
            ->roles
            ->transform(
                fn(RoleIdentifier $roleIdentifier): string => $roleIdentifier->toString()
            );
    }

    /**
     * @return ArrayList<string>
     */
    private function transformPermissionGroupsIdentifiersToString(): ArrayList
    {
        return $this
            ->permissionsGroups
            ->transform(
                fn(PermissionGroupsIdentifier $groupsIdentifier): string => $groupsIdentifier->toString()
            );
    }
}
