<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Client;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientCreateException;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientPersisterInterface;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryInterface;
use App\Shared\Domain\Scope\Scope;
use App\Shared\Infrastructure\Command\Runner\CliCommandRunner;
use App\Shared\Infrastructure\Transaction\TransactionServiceInterface;
use RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;

final class PublicApiClientPersister implements ClientPersisterInterface
{
    public function __construct(
        private TransactionServiceInterface $transactionService,
        private CliCommandRunner $commandRunner,
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
                    $this->commandRunner->run(
                        new ArrayInput(
                            [
                                'command' => 'league:oauth2-server:create-client',
                                'name' => $client->getClientId()->asString(),
                                'identifier' => $client->getClientId()->asString(),
                                'secret' => $client->getClientSecret()->asString(),
                                '--scope' => [
                                    Scope::publicApi()->asString(),
                                ],
                                '--grant-type' => [
                                    'client_credentials',
                                ],
                            ]
                        )
                    );

                    $this->clientRepository->save($client);
                }
            );
        } catch (RuntimeException $e) {
            throw new ClientCreateException($e->getMessage());
        }
    }
}
