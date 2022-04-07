<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

final class ClientIdFactory
{
    private const HASH_ALGORITHM = 'md5';

    public function generate(): ClientIdentifier
    {
        return new ClientIdentifier(hash(self::HASH_ALGORITHM, random_bytes(16)));
    }
}
