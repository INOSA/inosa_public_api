<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetUsers\Infrastructure;

use App\AuthorizationServer\GetUsers\Domain\GetUsersHttpClientInterface;
use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Infrastructure\Http\HttpClient;
use App\Shared\Infrastructure\Response\ProxyResponseMapper;

final class GetUsersHttpClient implements GetUsersHttpClientInterface
{
    public function __construct(private HttpClient $httpClient, private ProxyResponseMapper $proxyResponseMapper)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        $request = $this->httpClient->get($endpoint->getUrl());

        return $this->proxyResponseMapper->toProxyResponse($request);
    }
}
