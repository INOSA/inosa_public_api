<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDepartments\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class GetDepartmentsApi
{
    public function __construct(private GetDepartmentsHttpClientInterface $httpClient)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        return $this->httpClient->request($endpoint);
    }
}
