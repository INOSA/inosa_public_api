<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ArchiveUser\Application\Command;

use App\AuthorizationServer\ArchiveUser\Domain\KeepRole;
use App\Shared\Application\MessageBus\SyncCommandInterface;
use App\Shared\Domain\Identifier\UserIdentifier;

final class ArchiveUserCommand implements SyncCommandInterface
{
    public function __construct(
        public readonly UserIdentifier $userIdentifier,
        public readonly KeepRole $keepRole,
        public readonly ?UserIdentifier $deviationsResponsibleUserId,
        public readonly ?UserIdentifier $actionsResponsibleUserId,
        public readonly ?UserIdentifier $itemsResponsibleId,
        public readonly ?UserIdentifier $coSignerResponsibleId,
    ) {
    }
}
