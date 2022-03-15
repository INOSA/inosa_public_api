<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerUser\Application;

use App\Shared\Domain\Identifier\DepartmentIdentifier;
use Inosa\Arrays\ArrayList;

final class GetReadingStatusPerUserRequest
{
    /**
     * @param ArrayList<DepartmentIdentifier> $departmentIds
     */
    public function __construct(public readonly ArrayList $departmentIds)
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
