<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoleStatusPerUser\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;

final class GetRoleStatusPerUserEndpoint implements PostEndpointInterface
{
    private const URL = 'reports/competence/role-status-per-user';

    /**
     * @param ArrayList<RoleIdentifier> $roleIds
     */
    public function __construct(private ArrayList $roleIds)
    {
    }

    public function getUrl(): Url
    {
        return new Url(sprintf('%s', self::URL));
    }

    /**
     * @inheritDoc
     */
    public function getParams(): ArrayHashMap
    {
        return ArrayHashMap::create(
            [
                'globalFilters' => [],
                'localFilters' => [
                    'roleIds' => $this
                        ->roleIds
                        ->transform(fn(RoleIdentifier $identifier): string => $identifier->toString())
                        ->toArray(),
                ],
                'pagination' => [
                    'page' => 0,
                    'size' => -1,
                ],
            ]
        );
    }
}
