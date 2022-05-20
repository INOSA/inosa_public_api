<?php

declare(strict_types=1);

namespace App\AuthorizationServer\DeleteUser\Application\Command;

use App\Shared\Application\MessageBus\SyncCommandInterface;
use App\Shared\Domain\Identifier\UserIdentifier;

final class DeleteUserCommand implements SyncCommandInterface
{
    public function __construct(
        public readonly UserIdentifier $userToDeleteId,
        public readonly ?UserIdentifier $substituteItemsResponsibleId = null,
        public readonly ?UserIdentifier $substituteCoSignerId = null,
    ) {
    }
}
