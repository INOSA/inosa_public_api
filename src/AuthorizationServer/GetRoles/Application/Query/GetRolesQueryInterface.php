<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoles\Application\Query;

use App\Shared\Application\Query\ResponseViewInterface;

interface GetRolesQueryInterface
{
    public function getRoles(): ResponseViewInterface;
}
