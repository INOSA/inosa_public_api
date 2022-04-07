<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetPermissionGroups\Application;

use App\Shared\Application\Query\ResponseViewInterface;

interface GetPermissionGroupsQueryInterface
{
    public function getGetPermissionGroups(): ResponseViewInterface;
}
