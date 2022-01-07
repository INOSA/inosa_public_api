<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Client;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientPersisterInterface;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryInterface;

final class PublicApiClientPersister implements ClientPersisterInterface
{
    public function __construct(private ClientRepositoryInterface $clientRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public function persist(Client $client): void
    {
        $this->clientRepository->save($client);
    }
}
