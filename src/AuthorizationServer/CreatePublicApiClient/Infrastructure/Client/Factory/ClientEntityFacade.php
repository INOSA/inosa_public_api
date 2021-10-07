<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Client\Factory;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientId;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryException;
use App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Client\Entity\ClientEntity;
use Trikoder\Bundle\OAuth2Bundle\Manager\Doctrine\ClientManager;

final class ClientEntityFacade
{
    public function __construct(private ClientManager $clientManager)
    {
    }

    /**
     * @throws ClientRepositoryException
     */
    public function toEntity(Client $clientAdditionalInfo): ClientEntity
    {
        $client = $this->clientManager->find($clientAdditionalInfo->getClientId()->asString());

        if (null === $client) {
            throw new ClientRepositoryException(
                sprintf(
                    'Could not find a client with hash %s',
                    $clientAdditionalInfo->getClientId()->asString()
                )
            );
        }

        return new ClientEntity(
            $clientAdditionalInfo->getClientInternalIdentifier(),
            $clientAdditionalInfo->getInosaSiteIdentifier(),
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
