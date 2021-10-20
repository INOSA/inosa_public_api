<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

use App\Shared\Domain\Identifier\InosaSiteIdentifier;

final class ClientFactory
{
    public function create(
        ClientInternalIdentifier $clientIdentifier,
        InosaSiteIdentifier $inosaSiteIdentifier,
        ClientId $clientId,
        ClientSecret $clientSecret,
    ): Client {
        return new Client($clientIdentifier, $inosaSiteIdentifier, $clientId, $clientSecret);
    }
}
