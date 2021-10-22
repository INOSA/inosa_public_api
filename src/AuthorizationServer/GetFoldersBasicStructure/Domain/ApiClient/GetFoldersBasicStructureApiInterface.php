<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Domain\ApiClient;

use App\AuthorizationServer\GetFoldersBasicStructure\Application\Query\GetFoldersBasicStructureView;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

interface GetFoldersBasicStructureApiInterface
{
    public function getFoldersBasicStructure(InosaSiteIdentifier $siteIdentifier): GetFoldersBasicStructureView|InternalServerErrorView;
}
