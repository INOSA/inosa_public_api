<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusForOneUser\Application\Query;

use App\Shared\Domain\Identifier\DocumentIdentifier;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use Inosa\Arrays\ArrayList;

final class GetReadingStatusForOneUserRequest
{
    /**
     * @param ArrayList<RoleIdentifier> $rolesIds
     * @param ArrayList<DocumentIdentifier> $documentsIds
     */
    public function __construct(public UserIdentifier $userId, public ArrayList $rolesIds, public ArrayList $documentsIds)
    {
    }
}
