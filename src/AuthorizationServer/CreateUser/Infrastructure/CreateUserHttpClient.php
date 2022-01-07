<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Infrastructure;

use App\AuthorizationServer\CreateUser\Domain\CreateUserHttpClientInterface;
use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Infrastructure\Http\HttpClient;
use App\Shared\Infrastructure\Response\ProxyResponseMapper;

final class CreateUserHttpClient implements CreateUserHttpClientInterface
{
    public function __construct(private HttpClient $httpClient, private ProxyResponseMapper $proxyResponseMapper)
    {
    }

    public function request(PostEndpointInterface $endpoint): ProxyResponse
    {
        $post = $this->httpClient->post($endpoint->getUrl(), $endpoint->getParams());

        return $this->proxyResponseMapper->toProxyResponse($post);
    }
}
