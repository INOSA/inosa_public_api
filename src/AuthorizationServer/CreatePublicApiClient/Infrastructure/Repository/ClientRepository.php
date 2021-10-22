<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Repository;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryException;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Infrastructure\Client\Entity\ClientEntity;
use App\Shared\Infrastructure\Client\Factory\ClientEntityFacade;
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
            /** @var ClientEntity $entity */
            $entity = $qb->getQuery()->getSingleResult();

            return $this->clientFacade->toModel($entity);
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException $e) {
            throw new ClientRepositoryException($e->getMessage());
        }
    }
}
