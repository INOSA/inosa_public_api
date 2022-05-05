<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain;

use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\Identifier\PermissionGroupsIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;

final class ConnectUsersToPermissionsGroupsEndpoint implements PutEndpointInterface
{
    public const URL = 'users/%s/permissions-groups';

    /**
     * @param ArrayList<PermissionGroupsIdentifier> $permissionsGroupsIds
     */
    public function __construct(
        private UserIdentifier $userIdentifier,
        private ArrayList $permissionsGroupsIds,
    ) {
    }

    public function getUrl(): Url
    {
        return new Url(sprintf(self::URL, $this->userIdentifier->toString()));
    }

    /**
     * @inheritDoc
     */
    public function getParams(): ArrayHashMap
    {
        return ArrayHashMap::create(
            [
                'permissionsGroupsIds' => $this
                    ->permissionsGroupsIds
                    ->transform(
                        fn(PermissionGroupsIdentifier $permissionGroupsId): string => $permissionGroupsId->toString()
                    )
                    ->toArray(),
            ]
        );
    }
}
