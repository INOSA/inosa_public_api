<?php

declare(strict_types=1);

namespace App\Shared\Domain\Identifier;

interface IdentifierFactoryInterface
{
    public function create(): Identifier;

    public function fromString(string $identifier): Identifier;
}
