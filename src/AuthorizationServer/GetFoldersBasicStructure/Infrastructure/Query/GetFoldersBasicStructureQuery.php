<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Infrastructure\Query;

use App\AuthorizationServer\GetFoldersBasicStructure\Application\Query\GetFoldersBasicStructureQueryInterface;
use App\AuthorizationServer\GetFoldersBasicStructure\Application\Query\GetFoldersBasicStructureView;
use App\AuthorizationServer\GetFoldersBasicStructure\Domain\ApiClient\GetFoldersBasicStructureApiInterface;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

final class GetFoldersBasicStructureQuery implements GetFoldersBasicStructureQueryInterface
{
    public function __construct(private GetFoldersBasicStructureApiInterface $inosaApi)
    {
    }

    public function getFolderBasicStructureView(InosaSiteIdentifier $siteIdentifier): GetFoldersBasicStructureView|InternalServerErrorView
    {
        return $this->inosaApi->getFoldersBasicStructure($siteIdentifier);
    }
}
