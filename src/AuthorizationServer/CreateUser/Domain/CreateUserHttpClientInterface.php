<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface CreateUserHttpClientInterface
{
    public function request(PostEndpointInterface $endpoint): ProxyResponse;
}
