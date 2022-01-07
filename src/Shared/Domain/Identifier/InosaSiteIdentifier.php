<?php

declare(strict_types=1);

namespace App\Shared\Domain\Identifier;

final class InosaSiteIdentifier extends Identifier
{
    public static function fromIdentifier(Identifier $identifier): self
    {
        return new self($identifier->toString());
    }
}
