<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoles\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface GetRolesHttpClientInterface
{
    public function request(EndpointInterface $endpoint): ProxyResponse;
}
