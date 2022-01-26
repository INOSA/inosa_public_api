<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerDepartment\Application\Query;

use App\Shared\Domain\Identifier\DepartmentIdentifier;
use Inosa\Arrays\ArrayList;

final class GetReadingStatusPerDepartmentRequest
{
    /**
     * @param ArrayList<DepartmentIdentifier> $departmentIds
     */
    public function __construct(public ArrayList $departmentIds)
    {
    }

    /**
     * @return ArrayList<DepartmentIdentifier>
     */
    public function getDepartmentIds(): ArrayList
    {
        return $this->departmentIds;
    }
}
