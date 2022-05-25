<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDocumentsPerStatus\Infrastructure;

use App\AuthorizationServer\GetDocumentsPerStatus\Domain\GetDocumentsPerStatusHttpClientInterface;
use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Infrastructure\Http\ReportsHttpClient;
use App\Shared\Infrastructure\Response\ProxyResponseMapper;

final class GetDocumentsPerStatusHttpClient implements GetDocumentsPerStatusHttpClientInterface
{
    public function __construct(
        private readonly ReportsHttpClient $reportsHttpClient,
        private readonly ProxyResponseMapper $proxyResponseMapper,
    ) {
    }

    public function request(PostEndpointInterface $postEndpoint): ProxyResponse
    {
        $response = $this->reportsHttpClient->post($postEndpoint->getUrl(), $postEndpoint->getParams());

        return $this->proxyResponseMapper->toProxyResponse($response);
    }
}
