<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\Infrastructure;

use App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain\ConnectUserToPermissionsGroupsHttpClientInterface;
use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Infrastructure\Http\HttpClient;
use App\Shared\Infrastructure\Response\ProxyResponseMapper;

final class ConnectUserToPermissionsGroupHttpClient implements ConnectUserToPermissionsGroupsHttpClientInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly ProxyResponseMapper $proxyResponseMapper,
    ) {
    }

    public function request(PutEndpointInterface $endpoint): ProxyResponse
    {
        $response = $this->httpClient->put($endpoint->getUrl(), $endpoint->getParams());

        return $this->proxyResponseMapper->toProxyResponse($response);
    }
}
