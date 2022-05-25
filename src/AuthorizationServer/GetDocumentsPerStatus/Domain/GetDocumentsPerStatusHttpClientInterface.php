<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDocumentsPerStatus\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface GetDocumentsPerStatusHttpClientInterface
{
    public function request(PostEndpointInterface $postEndpoint): ProxyResponse;
}
