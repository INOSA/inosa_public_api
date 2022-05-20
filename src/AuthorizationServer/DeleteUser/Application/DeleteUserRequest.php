<?php

declare(strict_types=1);

namespace App\AuthorizationServer\DeleteUser\Application;

final class DeleteUserRequest
{
    public function __construct(
        public readonly string $userId,
        public readonly ?string $itemsResponsibleId = null,
        public readonly ?string $coSignerResponsibleId = null,
    ) {
    }
}
