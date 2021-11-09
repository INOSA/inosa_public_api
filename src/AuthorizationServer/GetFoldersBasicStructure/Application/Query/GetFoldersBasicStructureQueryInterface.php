<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Application\Query;

use App\Shared\Application\Query\InternalServerErrorView;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

interface GetFoldersBasicStructureQueryInterface
{
    public function getFolderBasicStructureView(InosaSiteIdentifier $siteIdentifier): GetFoldersBasicStructureView|InternalServerErrorView;
}
