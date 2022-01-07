<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToRoles\Domain;

use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;

final class ConnectUserToRolesEndpoint implements PutEndpointInterface
{
    private const URL = 'users/%s/roles';

    /**
     * @param ArrayList<RoleIdentifier> $roles
     */
    public function __construct(public readonly UserIdentifier $userIdentifier, public readonly ArrayList $roles)
    {
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
                'rolesIds' => $this
                    ->roles
                    ->transform(fn(RoleIdentifier $roleIdentifier): string => $roleIdentifier->toString())
                    ->toArray(),
            ],
        );
    }
}
