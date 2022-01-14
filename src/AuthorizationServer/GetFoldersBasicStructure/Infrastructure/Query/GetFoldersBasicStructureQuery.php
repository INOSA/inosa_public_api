<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Infrastructure\Query;

use App\AuthorizationServer\GetFoldersBasicStructure\Application\Query\GetFoldersBasicStructureQueryInterface;
use App\AuthorizationServer\GetFoldersBasicStructure\Domain\ApiClient\GetFoldersBasicStructureApi;
use App\AuthorizationServer\GetFoldersBasicStructure\Domain\Endpoint\GetFoldersBasicStructureEndpoint;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\ResponseViewInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

final class GetFoldersBasicStructureQuery implements GetFoldersBasicStructureQueryInterface
{
    public function __construct(private GetFoldersBasicStructureApi $api, private ViewFactory $viewFactory)
    {
    }

    public function getFolderBasicStructureView(InosaSiteIdentifier $inosaSiteIdentifier): ResponseViewInterface
    {
        $proxyResponse = $this->api->request(new GetFoldersBasicStructureEndpoint($inosaSiteIdentifier));

        return $this->viewFactory->fromProxyResponse($proxyResponse);
    }
}
