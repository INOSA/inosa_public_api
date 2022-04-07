<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetUsers\Application;

use App\Shared\Application\Query\ResponseViewInterface;

interface GetUsersQueryInterface
{
    public function getUsers(GetUsersRequest $request): ResponseViewInterface;
}
