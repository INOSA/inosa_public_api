<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerDepartment\Infrastructure;

use App\AuthorizationServer\GetReadingStatusPerDepartment\Domain\GetReadingStatusPerDepartmentHttpClientInterface;
use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Infrastructure\Http\ReportsHttpClient;
use App\Shared\Infrastructure\Response\ProxyResponseMapper;

final class GetReadingStatusPerDepartmentHttpClient implements GetReadingStatusPerDepartmentHttpClientInterface
{
    public function __construct(private ReportsHttpClient $httpClient, private ProxyResponseMapper $proxyResponseMapper)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        $response = $this->httpClient->get($endpoint->getUrl());

        return $this->proxyResponseMapper->toProxyResponse($response);
    }
}
