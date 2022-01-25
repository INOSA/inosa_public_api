<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerDepartment\Application\Query;

use App\Shared\Application\Query\ResponseViewInterface;

interface GetReadingStatusPerDepartmentQueryInterface
{
    public function getReadingStatusPerDepartmentView(GetReadingStatusPerDepartmentRequest $request): ResponseViewInterface;
}
