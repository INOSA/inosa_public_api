<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDocumentsPerStatus\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class GetDocumentsPerStatusApi
{
    public function __construct(private readonly GetDocumentsPerStatusHttpClientInterface $httpClient)
    {
    }

    public function request(PostEndpointInterface $postEndpoint): ProxyResponse
    {
        return $this->httpClient->request($postEndpoint);
    }
}
