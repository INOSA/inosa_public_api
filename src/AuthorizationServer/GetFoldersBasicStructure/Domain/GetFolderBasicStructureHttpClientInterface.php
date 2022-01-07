<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

interface GetFolderBasicStructureHttpClientInterface
{
    public function request(EndpointInterface $endpoint): ProxyResponse;
}
