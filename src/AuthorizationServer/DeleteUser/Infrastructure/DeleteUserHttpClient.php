<?php

declare(strict_types=1);

namespace App\AuthorizationServer\DeleteUser\Infrastructure;

use App\AuthorizationServer\DeleteUser\Domain\DeleteUserHttpClientInterface;
use App\Shared\Domain\Endpoint\DeleteEndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Infrastructure\Http\HttpClient;
use App\Shared\Infrastructure\Response\ProxyResponseMapper;

final class DeleteUserHttpClient implements DeleteUserHttpClientInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly ProxyResponseMapper $proxyResponseMapper,
    ) {
    }

    public function request(DeleteEndpointInterface $endpoint): ProxyResponse
    {
        $response = $this->httpClient->delete($endpoint->getUrl(), $endpoint->getParams());

        return $this->proxyResponseMapper->toProxyResponse($response);
    }
}
