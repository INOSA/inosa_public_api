<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

use App\Shared\Domain\Identifier\InosaSiteIdentifier;

final class ClientFactory
{
    public function __construct(
        private ClientIdFactory $clientIdFactory,
        private ClientSecretFactory $clientSecretFactory,
    ) {
    }

    public function create(InosaSiteIdentifier $inosaSiteIdentifier): Client
    {
        return new Client(
            $inosaSiteIdentifier->toClientInternalIdentifier(),
            $inosaSiteIdentifier,
            $this->clientIdFactory->generate(),
            $this->clientSecretFactory->generate()
        );
    }
}
