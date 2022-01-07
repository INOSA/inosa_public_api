<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Infrastructure;

use App\AuthorizationServer\GetFoldersBasicStructure\Domain\GetFolderBasicStructureHttpClientInterface;
use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Infrastructure\Http\HttpClient;
use App\Shared\Infrastructure\Response\ProxyResponseMapper;

final class GetFolderBasicStructureHttpClient implements GetFolderBasicStructureHttpClientInterface
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
