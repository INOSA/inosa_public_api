<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetPermissionGroups\Infrastructure;

use App\AuthorizationServer\GetPermissionGroups\Domain\GetPermissionGroupsHttpClientInterface;
use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Infrastructure\Http\HttpClient;
use App\Shared\Infrastructure\Response\ProxyResponseMapper;

final class GetPermissionGroupsHttClient implements GetPermissionGroupsHttpClientInterface
{
    public function __construct(private HttpClient $httpClient, private ProxyResponseMapper $proxyResponseMapper)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        return $this->proxyResponseMapper->toProxyResponse($this->httpClient->get($endpoint->getUrl()));
    }
}
