<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusForOneUser\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface GetReadingStatusForOneUserHttpClientInterface
{
    public function request(PostEndpointInterface $endpoint): ProxyResponse;
}
