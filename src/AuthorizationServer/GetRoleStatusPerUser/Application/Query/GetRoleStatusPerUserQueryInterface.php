<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoleStatusPerUser\Application\Query;

use App\Shared\Application\Query\ResponseViewInterface;

interface GetRoleStatusPerUserQueryInterface
{
    public function getRoleStatusPerUserView(GetRoleStatusPerUserRequest $request): ResponseViewInterface;
}
