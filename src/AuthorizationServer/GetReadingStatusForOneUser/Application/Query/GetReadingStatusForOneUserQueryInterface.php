<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusForOneUser\Application\Query;

use App\Shared\Application\Query\ResponseViewInterface;

interface GetReadingStatusForOneUserQueryInterface
{
    public function getReadingStatusForOneUserView(GetReadingStatusForOneUserRequest $request): ResponseViewInterface;
}
