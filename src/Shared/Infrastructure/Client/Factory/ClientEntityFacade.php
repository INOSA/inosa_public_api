<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Client\Factory;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client as DomainClient;
use App\Shared\Infrastructure\Client\Entity\Client;

final class ClientEntityFacade
{
    public function toEntity(DomainClient $domainClient): Client
    {
        return new Client(
            $domainClient->getClientInternalIdentifier(),
            $domainClient->getSiteId(),
            $domainClient->getClientName(),
            $domainClient->getClientId(),
            $domainClient->getClientSecret(),
            $domainClient->getGrants(),
            $domainClient->getScopes()
        );
    }

    public function toModel(Client $entity): DomainClient
    {
        return new DomainClient(
            $entity->getId(),
            $entity->getInosaSiteId(),
            $entity->getClientName(),
            $entity->getClientIdentifier(),
            $entity->getClientSecret(),
            $entity->getClientGrants(),
            $entity->getClientScopes()
        );
    }
}
