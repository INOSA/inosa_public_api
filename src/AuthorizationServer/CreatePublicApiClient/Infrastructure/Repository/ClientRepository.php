<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Repository;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Infrastructure\Client\Factory\ClientEntityFacade;
use App\Shared\Infrastructure\Client\Repository\ClientEntityNotFoundException;
use App\Shared\Infrastructure\Client\Repository\ClientRepository as ClientEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClientEntityFacade $clientFacade,
        private ClientEntityRepository $clientRepository,
    ) {
    }

    public function save(Client $client): void
    {
        $this->entityManager->persist($this->clientFacade->toEntity($client));
    }

    public function findOneByInosaSiteIdentifier(InosaSiteIdentifier $id): ?Client
    {
        try {
            $entity = $this->clientRepository->getClientByInosaSiteIdentifier($id);

            return $this->clientFacade->toModel($entity);
        } catch (ClientEntityNotFoundException) {
            return null;
        }
    }
}
