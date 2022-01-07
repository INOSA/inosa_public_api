<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoles\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\Url\Url;

final class GetRolesEndpoint implements EndpointInterface
{
    private const URL = 'roles';

    public function getUrl(): Url
    {
        return new Url(self::URL);
    }
}
