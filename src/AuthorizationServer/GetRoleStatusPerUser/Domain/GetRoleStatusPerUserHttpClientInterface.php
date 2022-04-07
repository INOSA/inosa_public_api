<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoleStatusPerUser\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface GetRoleStatusPerUserHttpClientInterface
{
    public function request(PostEndpointInterface $endpoint): ProxyResponse;
}
