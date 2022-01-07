<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

/**
 * String identifier that is created by factory, used for internal connection in oauth library
 */
final class ClientIdentifier
{
    public function __construct(private string $clientId)
    {
    }

    public function toString(): string
    {
        return $this->clientId;
    }
}
