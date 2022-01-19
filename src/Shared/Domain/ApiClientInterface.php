<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;

interface ApiClientInterface
{
    public function request(EndpointInterface $endpoint): ProxyResponse;
}
