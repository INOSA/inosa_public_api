<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Client\Repository;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryException;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryInterface;
use App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Client\Entity\ClientEntity;
use App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Client\Factory\ClientEntityFacade;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

final class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClientEntityFacade $clientFacade,
    ) {
    }

    public function save(Client $client): void
    {
        $this->entityManager->persist($this->clientFacade->toEntity($client));
    }

    /**
     * @throws ClientRepositoryException
     */
    public function findOneByInosaSiteIdentifier(InosaSiteIdentifier $id): ?Client
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('client_additional_info')
            ->from(ClientEntity::class, 'client_additional_info')
            ->andWhere(
                $qb->expr()->eq('client_additional_info.inosaSiteId', ':inosaSiteId')
            )->setParameter(
                'inosaSiteId',
                $id->asString()
            );

        try {
            return $this->clientFacade->toModel(
                $qb->getQuery()->getSingleResult()
            );
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException $e) {
            throw new ClientRepositoryException($e->getMessage());
        }
    }
}
