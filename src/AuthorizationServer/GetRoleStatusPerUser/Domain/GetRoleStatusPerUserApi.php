<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoleStatusPerUser\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class GetRoleStatusPerUserApi
{
    public function __construct(private GetRoleStatusPerUserHttpClientInterface $httpClient)
    {
    }

    public function request(PostEndpointInterface $endpoint): ProxyResponse
    {
        return $this->httpClient->request($endpoint);
    }
}
