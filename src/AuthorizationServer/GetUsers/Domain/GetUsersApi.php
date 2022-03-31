<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetUsers\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class GetUsersApi
{
    public function __construct(private GetUsersHttpClientInterface $getUsersHttpClient)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        return $this->getUsersHttpClient->request($endpoint);
    }
}
