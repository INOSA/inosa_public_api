<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Security\Passport;

use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

final class ScopeBadge implements BadgeInterface
{
    /**
     * @param string[] $scopes
     */
    public function __construct(
        private array $scopes,
        private bool $resolved = false
    ) {
    }

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function isResolved(): bool
    {
        return $this->resolved;
    }

    public function markResolved(): void
    {
        $this->resolved = true;
    }
}
