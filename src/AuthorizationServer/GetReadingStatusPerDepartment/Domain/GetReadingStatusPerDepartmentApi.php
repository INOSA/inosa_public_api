<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerDepartment\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class GetReadingStatusPerDepartmentApi
{
    public function __construct(private GetReadingStatusPerDepartmentHttpClientInterface $httpClient)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        return $this->httpClient->request($endpoint);
    }
}
