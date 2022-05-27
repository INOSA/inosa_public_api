<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDepartments\Application;

use App\Shared\Application\Query\ResponseViewInterface;

interface GetDepartmentsQueryInterface
{
    public function getDepartments(): ResponseViewInterface;
}
