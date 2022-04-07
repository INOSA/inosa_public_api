<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Application\Query;

use App\Shared\Application\Query\ResponseViewInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

interface GetFoldersBasicStructureQueryInterface
{
    public function getFolderBasicStructureView(InosaSiteIdentifier $inosaSiteIdentifier): ResponseViewInterface;
}
