<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerUser\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class GetReadingStatusPerUserApi
{
    public function __construct(private GetReadingStatusPerUserHttpClientInterface $httpClient)
    {
    }

    public function request(PostEndpointInterface $endpoint): ProxyResponse
    {
        return $this->httpClient->request($endpoint);
    }
}
