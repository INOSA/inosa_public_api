<?php

declare(strict_types=1);

namespace App\Shared\Domain\Identifier;

class Identifier
{
    /**
     * Always use @link IdentifierFactoryInterface for instantiation
     */
    public function __construct(private string $identifier)
    {
    }

    public function __toString(): string
    {
        return $this->asString();
    }

    public function asString(): string
    {
        return $this->identifier;
    }
}
