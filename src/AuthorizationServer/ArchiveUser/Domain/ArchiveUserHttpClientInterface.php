<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ArchiveUser\Domain;

use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface ArchiveUserHttpClientInterface
{
    public function request(PutEndpointInterface $endpoint): ProxyResponse;
}
