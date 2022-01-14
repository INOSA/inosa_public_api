<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerDepartment\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface GetReadingStatusPerDepartmentHttpClientInterface
{
    public function request(EndpointInterface $endpoint): ProxyResponse;
}
