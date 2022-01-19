<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDepartments\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface GetDepartmentsHttpClientInterface
{
    public function request(EndpointInterface $endpoint): ProxyResponse;
}
