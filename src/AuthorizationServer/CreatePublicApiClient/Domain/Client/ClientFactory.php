<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Domain\Scope\Scope;
use Inosa\Arrays\ArrayList;

final class ClientFactory
{
    public function __construct(
        private ClientIdFactory $clientIdFactory,
        private ClientSecretFactory $clientSecretFactory,
        private IdentifierFactoryInterface $identifierFactory,
    ) {
    }

    /**
     * @param ArrayList<Grant> $grants
     * @param ArrayList<Scope> $scopes
     */
    public function create(
        InosaSiteIdentifier $inosaSiteIdentifier,
        ClientName $clientName,
        ArrayList $grants,
        ArrayList $scopes,
    ): Client {
        return new Client(
            ClientInternalIdentifier::fromIdentifier($this->identifierFactory->create()),
            $inosaSiteIdentifier,
            $clientName,
            $this->clientIdFactory->generate(),
            $this->clientSecretFactory->generate(),
            $grants,
            $scopes
        );
    }
}
