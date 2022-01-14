<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Client\Repository;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientIdentifier;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Infrastructure\Client\Entity\Client;
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

    public function getClientFromToken(): Client
    {
        $oauthToken = $this->tokenStorage->getToken();

        if (null === $oauthToken || !is_string($token = $oauthToken->getAttribute('access_token_id'))) {
            throw new LogicException('Authorization token does not exists');
        }

        $token = $this->accessTokenManager->find($token);

        if (null === $token) {
            throw new LogicException('Token does not exists');
        }

        $client = $token->getClient();

        if (!is_a($client, Client::class)) {
            throw new LogicException('Invalid client created');
        }

        return $client;
    }

    /**
     * @throws ClientEntityNotFoundException
     */
    public function getByInternalClientIdentifier(ClientIdentifier $clientIdentifier): Client
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('client')
            ->from(Client::class, 'client')
            ->andWhere(
                $qb->expr()->eq('client.identifier', ':clientIdentifier')
            )->setParameter(
                'clientIdentifier',
                $clientIdentifier->toString()
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

    /**
     * @throws ClientEntityNotFoundException
     */
    public function getByInosaSiteIdentifier(InosaSiteIdentifier $id): Client
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('client')
            ->from(Client::class, 'client')
            ->andWhere(
                $qb->expr()->eq('client.siteId', ':siteId')
            )->setParameter(
                'siteId',
                $id->toString()
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
