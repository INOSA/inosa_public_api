<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

final class ClientId
{
    public function __construct(private string $clientId)
    {
    }

    public function asString(): string
    {
        return $this->clientId;
    }
}
