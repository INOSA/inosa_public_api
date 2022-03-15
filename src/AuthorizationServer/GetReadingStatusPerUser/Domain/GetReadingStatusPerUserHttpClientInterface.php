<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerUser\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface GetReadingStatusPerUserHttpClientInterface
{
    public function request(PostEndpointInterface $endpoint): ProxyResponse;
}
