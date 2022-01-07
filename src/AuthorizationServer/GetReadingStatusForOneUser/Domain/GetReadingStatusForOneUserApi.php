<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusForOneUser\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class GetReadingStatusForOneUserApi
{
    public function __construct(private GetReadingStatusForOneUserHttpClientInterface $httpClient)
    {
    }

    public function request(PostEndpointInterface $endpoint): ProxyResponse
    {
        return $this->httpClient->request($endpoint);
    }
}
