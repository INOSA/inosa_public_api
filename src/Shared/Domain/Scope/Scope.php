<?php

declare(strict_types=1);

namespace App\Shared\Domain\Scope;

final class Scope
{
    private const SCOPE_PUBLIC_API = 'public-api';

    private function __construct(private string $scope)
    {
    }

    public static function fromString(string $scope): self
    {
        return new self($scope);
    }

    public static function publicApi(): self
    {
        return new self(self::SCOPE_PUBLIC_API);
    }

    public function toString(): string
    {
        return $this->scope;
    }
}
