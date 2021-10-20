<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Client;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientCreateException;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientId;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientPersisterInterface;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryInterface;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientSecret;
use App\Shared\Domain\InosaApi\InosaApiInterface;
use App\Shared\Domain\Scope\Scope;
use App\Shared\Domain\Transaction\TransactionServiceInterface;
use App\Shared\Infrastructure\Command\Runner\CliCommandRunner;
use RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;

final class ClientPersister implements ClientPersisterInterface
{
    public function __construct(
        private TransactionServiceInterface $transactionService,
        private CliCommandRunner $commandRunner,
        private InosaApiInterface $apiClient,
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    public function persist(Client $client): void
    {
        try {
            $this->transactionService->execute(
                function () use ($client): void {
                    $this->commandRunner->run(
                        $this->getCreateClientCommandArrayInput($client->getClientId(), $client->getClientSecret())
                    );
                    $this->clientRepository->save($client);
                    $this->apiClient->createInosaPublicApiUser($client->getClientInternalIdentifier());
                }
            );
        } catch (RuntimeException $e) {
            throw new ClientCreateException($e->getMessage());
        }
    }

    private function getCreateClientCommandArrayInput(ClientId $clientId, ClientSecret $secret): ArrayInput
    {
        return new ArrayInput(
            [
                'command' => 'trikoder:oauth2:create-client',
                'identifier' => $clientId->asString(),
                'secret' => $secret->asString(),
                '--scope' => [
                    Scope::publicApi()->asString(),
                ],
                '--grant-type' => [
                    'client_credentials',
                ]
            ]
        );
    }
}
