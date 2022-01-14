<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

final class ClientSecret
{
    public function __construct(private string $secret)
    {
    }

    public function toString(): string
    {
        return $this->secret;
    }
}
