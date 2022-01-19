<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

final class Grant
{
    public const CLIENT_CREDENTIALS = 'client_credentials';

    private function __construct(private string $grant)
    {
    }

    public static function fromString(string $grant): self
    {
        return new self($grant);
    }

    public static function clientCredentials(): self
    {
        return new self(self::CLIENT_CREDENTIALS);
    }

    public function toString(): string
    {
        return $this->grant;
    }
}
