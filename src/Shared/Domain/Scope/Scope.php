<?php

declare(strict_types=1);

namespace App\Shared\Domain\Scope;

final class Scope
{
    private const SCOPE_PUBLIC_API = 'public-api';

    private function __construct(private string $scope)
    {
    }

    public static function publicApi(): self
    {
        return new self(self::SCOPE_PUBLIC_API);
    }

    public function asString(): string
    {
        return $this->scope;
    }
}
