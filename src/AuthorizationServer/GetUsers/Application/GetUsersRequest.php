<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetUsers\Application;

use App\AuthorizationServer\CreateUser\Domain\User\Email;
use App\AuthorizationServer\CreateUser\Domain\User\FirstName;
use App\AuthorizationServer\CreateUser\Domain\User\LastName;
use App\AuthorizationServer\CreateUser\Domain\User\UserName;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use Inosa\Arrays\ArrayList;

final class GetUsersRequest
{
    /**
     * @param ArrayList<DepartmentIdentifier> $departmentIds
     */
    public function __construct(
        public readonly ArrayList $departmentIds,
        public readonly ?FirstName $firstName,
        public readonly ?LastName $lastName,
        public readonly ?UserName $userName,
        public readonly ?Email $email,
    ) {
    }
}
