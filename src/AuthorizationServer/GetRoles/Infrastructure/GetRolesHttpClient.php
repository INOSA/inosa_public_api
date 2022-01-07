<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoles\Infrastructure;

use App\AuthorizationServer\GetRoles\Domain\GetRolesHttpClientInterface;
use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Infrastructure\Http\HttpClient;
use App\Shared\Infrastructure\Response\ProxyResponseMapper;

final class GetRolesHttpClient implements GetRolesHttpClientInterface
{
    public function __construct(private HttpClient $httpClient, private ProxyResponseMapper $proxyResponseMapper)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        $response = $this->httpClient->get($endpoint->getUrl());

        return $this->proxyResponseMapper->toProxyResponse($response);
    }
}
