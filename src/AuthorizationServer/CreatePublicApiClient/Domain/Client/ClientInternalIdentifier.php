<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

use App\Shared\Domain\Identifier\Identifier;

final class ClientInternalIdentifier extends Identifier
{
    public static function fromIdentifier(Identifier $identifier): self
    {
        return new self($identifier->asString());
    }
}
