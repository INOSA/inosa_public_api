<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repository;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientIdentifier;
use App\Shared\Infrastructure\AccessToken;
use App\Shared\Infrastructure\Client\Repository\ClientRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

final class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function __construct(
        private AccessTokenRepositoryInterface $repository,
        private ClientRepository $clientRepository,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): AccessTokenEntityInterface
    {
        $client = $this->clientRepository->getByInternalClientIdentifier(
            new ClientIdentifier($clientEntity->getIdentifier())
        );

        $accessToken = new AccessToken($client);
        $accessToken->setClient($clientEntity);
        /** @var int|string|null $userIdentifier */
        $accessToken->setUserIdentifier($userIdentifier);

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        return $accessToken;
    }

    /**
     * @inheritDoc
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        $this->repository->persistNewAccessToken($accessTokenEntity);
    }

    /**
     * @inheritDoc
     */
    public function revokeAccessToken($tokenId): mixed
    {
        return $this->repository->revokeAccessToken($tokenId);
    }

    /**
     * @inheritDoc
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        return $this->repository->isAccessTokenRevoked($tokenId);
    }
}
