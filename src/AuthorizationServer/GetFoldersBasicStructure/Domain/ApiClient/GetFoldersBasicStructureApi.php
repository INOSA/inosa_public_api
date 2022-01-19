<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Domain\ApiClient;

use App\AuthorizationServer\GetFoldersBasicStructure\Domain\GetFolderBasicStructureHttpClientInterface;
use App\Shared\Domain\ApiClientInterface;
use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class GetFoldersBasicStructureApi implements ApiClientInterface
{
    public function __construct(private GetFolderBasicStructureHttpClientInterface $httpClient)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        return $this->httpClient->request($endpoint);
    }
}
