<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ArchiveUser\Application;

final class ArchiveUserRequest
{
    public function __construct(
        public readonly string $userId,
        public readonly ?bool $shouldKeepRoles = null,
        public readonly ?string $deviationsResponsibleId = null,
        public readonly ?string $actionsResponsibleId = null,
        public readonly ?string $itemsResponsibleId = null,
        public readonly ?string $coSignerResponsibleId = null,
    ) {
    }
}
