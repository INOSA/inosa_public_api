<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToRoles\Infrastructure;

use App\AuthorizationServer\ConnectUserToRoles\Domain\ConnectUserToRolesHttpClientInterface;
use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Infrastructure\Http\HttpClient;
use App\Shared\Infrastructure\Response\ProxyResponseMapper;

final class ConnectUserToRolesHttpClient implements ConnectUserToRolesHttpClientInterface
{
    public function __construct(private HttpClient $httpClient, private ProxyResponseMapper $proxyResponseMapper)
    {
    }

    public function request(PutEndpointInterface $endpoint): ProxyResponse
    {
        $response = $this->httpClient->put($endpoint->getUrl(), $endpoint->getParams());

        return $this->proxyResponseMapper->toProxyResponse($response);
    }
}
