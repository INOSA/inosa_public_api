<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Application\Query;

use App\Shared\Application\Query\ResponseViewInterface;

interface GetFoldersBasicStructureQueryInterface
{
    public function getFolderBasicStructureView(): ResponseViewInterface;
}
