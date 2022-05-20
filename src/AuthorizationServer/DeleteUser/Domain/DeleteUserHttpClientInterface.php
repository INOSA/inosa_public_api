<?php

declare(strict_types=1);

namespace App\AuthorizationServer\DeleteUser\Domain;

use App\Shared\Domain\Endpoint\DeleteEndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface DeleteUserHttpClientInterface
{
    public function request(DeleteEndpointInterface $endpoint): ProxyResponse;
}
