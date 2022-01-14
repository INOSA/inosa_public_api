<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDepartments\Application;

use App\Shared\Application\Query\ResponseViewInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

interface GetDepartmentsQueryInterface
{
    public function getDepartments(InosaSiteIdentifier $siteIdentifier): ResponseViewInterface;
}
