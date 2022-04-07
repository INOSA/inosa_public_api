<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToRoles\Application\Command;

use App\Shared\Application\MessageBus\SyncCommandInterface;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use Inosa\Arrays\ArrayList;

final class ConnectUserToRolesCommand implements SyncCommandInterface
{
    /**
     * @param ArrayList<RoleIdentifier> $roles
     */
    public function __construct(public readonly UserIdentifier $userIdentifier, public readonly ArrayList $roles)
    {
    }
}
