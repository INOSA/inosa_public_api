<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetPermissionGroups\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface GetPermissionGroupsHttpClientInterface
{
    public function request(EndpointInterface $endpoint): ProxyResponse;
}
