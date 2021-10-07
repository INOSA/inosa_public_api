<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

final class ClientIdFactory
{
    private const HASH_ALGORITHM = 'md5';

    public function generate(): ClientId
    {
        return new ClientId(hash(self::HASH_ALGORITHM, random_bytes(16)));
    }
}
