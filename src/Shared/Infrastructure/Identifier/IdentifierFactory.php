<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Identifier;

use App\Shared\Domain\Identifier\Identifier;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use Ramsey\Uuid\Uuid;

final class IdentifierFactory implements IdentifierFactoryInterface
{
    public function create(): Identifier
    {
        return new Identifier(Uuid::uuid4()->toString());
    }

    public function fromString(string $identifier): Identifier
    {
        return new Identifier(Uuid::fromString($identifier)->toString());
    }
}
