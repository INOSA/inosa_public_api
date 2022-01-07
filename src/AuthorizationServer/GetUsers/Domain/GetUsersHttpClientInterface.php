<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetUsers\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface GetUsersHttpClientInterface
{
    public function request(EndpointInterface $endpoint): ProxyResponse;
}
