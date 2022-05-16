<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ArchiveUser\Domain;

final class KeepRole
{
    private const DEFAULT_KEEP_ROLE_VALUE = true;

    public readonly bool $shouldKeepRole;

    public function __construct(?bool $shouldKeepRole)
    {
        $this->shouldKeepRole = $shouldKeepRole ?? self::DEFAULT_KEEP_ROLE_VALUE;
    }
}
