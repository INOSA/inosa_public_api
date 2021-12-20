<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Client\Repository;

use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Infrastructure\Client\Entity\ClientEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use League\Bundle\OAuth2ServerBundle\Manager\AccessTokenManagerInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ClientRepository
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private AccessTokenManagerInterface $accessTokenManager,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws ClientEntityNotFoundException
     */
    public function getClientFromToken(): ClientEntity
    {
        $oauthToken = $this->tokenStorage->getToken();

        if (null === $oauthToken || !is_string($token = $oauthToken->getCredentials())) {
            throw new LogicException('Authorization token does not exists');
        }

        $token = $this->accessTokenManager->find($token);

        if (null === $token) {
            throw new LogicException('Token does not exists');
        }

        $qb = $this->entityManager->createQueryBuilder();
        $qb->from(ClientEntity::class, 'client')
            ->select('client')
            ->andWhere($qb->expr()->eq('client.client', ':clientId'))
            ->setParameter('clientId', $token->getClient()->getIdentifier());

        try {
            /** @var ClientEntity */
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException) {
            throw new ClientEntityNotFoundException('Client application not found');
        } catch (NonUniqueResultException $e) {
            throw new LogicException($e->getMessage());
        }
    }

    /**
     * @throws ClientEntityNotFoundException
     */
    public function getClientByInosaSiteIdentifier(InosaSiteIdentifier $id): ClientEntity
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
            /** @phpstan-ignore-next-line */
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new ClientEntityNotFoundException($e->getMessage());
        } catch (NonUniqueResultException $e) {
            throw new LogicException($e->getMessage());
        }
    }
}
