<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

final class ClientSecretFactory
{
    private const CLIENT_SECRET_ALGORITHM = 'sha512';

    public function generate(): ClientSecret
    {
        return new ClientSecret(hash(self::CLIENT_SECRET_ALGORITHM, random_bytes(32)));
    }
}
