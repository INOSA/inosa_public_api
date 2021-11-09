<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Client\Factory;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientId;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryException;
use App\Shared\Infrastructure\Client\Entity\ClientEntity;
use League\Bundle\OAuth2ServerBundle\Manager\Doctrine\ClientManager;
use League\Bundle\OAuth2ServerBundle\Model\Client as OAuthServerClient;
use LogicException;

final class ClientEntityFacade
{
    public function __construct(private ClientManager $clientManager)
    {
    }

    /**
     * @throws ClientRepositoryException
     */
    public function toEntity(Client $domainClient): ClientEntity
    {
        $client = $this->clientManager->find($domainClient->getClientId()->asString());

        if (null === $client) {
            throw new ClientRepositoryException(
                sprintf(
                    'Could not find a client with hash %s',
                    $domainClient->getClientId()->asString()
                )
            );
        }

        if (!is_a($client, OAuthServerClient::class)) {
            throw new LogicException('Undefined client class');
        }

        return new ClientEntity(
            $domainClient->getClientInternalIdentifier(),
            $domainClient->getInosaSiteIdentifier(),
            $client
        );
    }

    public function toModel(ClientEntity $entity): Client
    {
        return new Client(
            $entity->getId(),
            $entity->getInosaSiteId(),
            new ClientId($entity->getClient()->getIdentifier()),
            $entity->getClientSecret()
        );
    }
}
