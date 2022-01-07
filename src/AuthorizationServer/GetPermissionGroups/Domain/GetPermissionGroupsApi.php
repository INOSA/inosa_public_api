<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetPermissionGroups\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class GetPermissionGroupsApi
{
    public function __construct(private GetPermissionGroupsHttpClientInterface $httpClient)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        return $this->httpClient->request($endpoint);
    }
}
