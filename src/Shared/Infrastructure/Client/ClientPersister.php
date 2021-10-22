<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Client;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Client;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientCreateException;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientId;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientPersisterInterface;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientRepositoryInterface;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientSecret;
use App\AuthorizationServer\CreatePublicApiClient\Domain\InosaApi\CreatePublicApiUserInosaApiInterface;
use App\Shared\Domain\Scope\Scope;
use App\Shared\Infrastructure\Command\Runner\CliCommandRunner;
use App\Shared\Infrastructure\Transaction\TransactionServiceInterface;
use RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;

final class ClientPersister implements ClientPersisterInterface
{
    public function __construct(
        private TransactionServiceInterface $transactionService,
        private CliCommandRunner $commandRunner,
        private CreatePublicApiUserInosaApiInterface $apiClient,
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
                        $this->getCreateClientCommandArrayInput($client->getClientId(), $client->getClientSecret())
                    );
                    $this->clientRepository->save($client);
                    $this->apiClient->createInosaPublicApiUser($client->getInosaSiteIdentifier());
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
                'command' => 'league:oauth2-server:create-client',
                'name' => $clientId->asString(),
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
