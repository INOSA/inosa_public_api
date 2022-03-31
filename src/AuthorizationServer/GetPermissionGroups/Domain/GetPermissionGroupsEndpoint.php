<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetPermissionGroups\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\Url\Url;

final class GetPermissionGroupsEndpoint implements EndpointInterface
{
    private const URL = 'permissions-groups';

    public function __construct()
    {
    }

    public function getUrl(): Url
    {
        return new Url(self::URL);
    }
}
