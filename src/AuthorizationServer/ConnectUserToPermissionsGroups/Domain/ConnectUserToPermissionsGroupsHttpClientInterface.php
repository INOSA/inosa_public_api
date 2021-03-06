<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain;

use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface ConnectUserToPermissionsGroupsHttpClientInterface
{
    public function request(PutEndpointInterface $endpoint): ProxyResponse;
}
