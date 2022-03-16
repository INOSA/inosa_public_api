<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerUser\Application;

use App\Shared\Application\Query\ResponseViewInterface;

interface GetReadingStatusPerUserQueryInterface
{
    public function getReadingStatusPerUser(GetReadingStatusPerUserRequest $request): ResponseViewInterface;
}
