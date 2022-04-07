<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoles\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class GetRolesApi
{
    public function __construct(private GetRolesHttpClientInterface $httpClient)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        return $this->httpClient->request($endpoint);
    }
}
