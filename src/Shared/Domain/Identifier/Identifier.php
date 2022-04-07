<?php

declare(strict_types=1);

namespace App\Shared\Domain\Identifier;

use App\Shared\Domain\StringableInterface;

class Identifier implements StringableInterface
{
    /**
     * Always use @link IdentifierFactoryInterface for instantiation
     */
    public function __construct(private string $identifier)
    {
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->identifier;
    }
}
