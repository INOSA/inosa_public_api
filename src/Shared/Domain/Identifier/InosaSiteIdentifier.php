<?php

declare(strict_types=1);

namespace App\Shared\Domain\Identifier;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientInternalIdentifier;

final class InosaSiteIdentifier extends Identifier
{
    public static function fromIdentifier(Identifier $identifier): self
    {
        return new self($identifier->asString());
    }

    public function toClientInternalIdentifier(): ClientInternalIdentifier
    {
        return ClientInternalIdentifier::fromIdentifier(new Identifier($this->asString()));
    }
}
