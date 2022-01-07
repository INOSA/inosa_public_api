<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToRoles\Domain;

use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface ConnectUserToRolesHttpClientInterface
{
    public function request(PutEndpointInterface $endpoint): ProxyResponse;
}
