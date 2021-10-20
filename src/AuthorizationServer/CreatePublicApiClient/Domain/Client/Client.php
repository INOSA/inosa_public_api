<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

use App\Shared\Domain\Identifier\InosaSiteIdentifier;

final class Client
{
    public function __construct(
        private ClientInternalIdentifier $clientInternalIdentifier,
        private InosaSiteIdentifier $inosaSiteIdentifier,
        private ClientId $clientId,
        private ClientSecret $clientSecret,
    ) {
    }

    public function getClientInternalIdentifier(): ClientInternalIdentifier
    {
        return $this->clientInternalIdentifier;
    }

    public function getInosaSiteIdentifier(): InosaSiteIdentifier
    {
        return $this->inosaSiteIdentifier;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    public function getClientSecret(): ClientSecret
    {
        return $this->clientSecret;
    }
}
