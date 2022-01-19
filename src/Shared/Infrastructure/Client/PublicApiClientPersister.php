<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Client;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientCreateException;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientPersisterInterface;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryInterface;
use App\Shared\Infrastructure\Transaction\TransactionServiceInterface;
use RuntimeException;

final class PublicApiClientPersister implements ClientPersisterInterface
{
    public function __construct(
        private TransactionServiceInterface $transactionService,
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function persist(Client $client): void
    {
        try {
            $this->transactionService->execute(
                function () use ($client): void {
                    $this->clientRepository->save($client);
                }
            );
        } catch (RuntimeException $e) {
            throw new ClientCreateException($e->getMessage());
        }
    }
}
