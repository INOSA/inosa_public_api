<?php

declare(strict_types=1);

namespace App\Shared\Domain\Identifier;

use App\Shared\Infrastructure\Identifier\InvalidIdentifierException;

interface IdentifierFactoryInterface
{
    public function create(): Identifier;

    /**
     * @throws InvalidIdentifierException
     */
    public function fromString(string $identifier): Identifier;
}
