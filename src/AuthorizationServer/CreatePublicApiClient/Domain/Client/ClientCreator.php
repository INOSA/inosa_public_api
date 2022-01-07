<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

use App\AuthorizationServer\CreatePublicApiClient\Domain\InosaApi\CreatePublicApiUserInosaApiInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Domain\Scope\Scope;
use Inosa\Arrays\ArrayList;
use LogicException;

final class ClientCreator
{
    public function __construct(
        private CreatePublicApiUserInosaApiInterface $apiClient,
        private ClientFactory $clientFactory,
        private ClientPersisterInterface $clientPersister,
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    /**
     * @throws ClientCreateException
     */
    public function create(InosaSiteIdentifier $inosaSiteIdentifier, ClientName $clientName): void
    {
        $this->assertSiteExists($inosaSiteIdentifier);

        if ($this->alreadyHasClient($inosaSiteIdentifier)) {
            return;
        }

        $client = $this->clientFactory->create(
            $inosaSiteIdentifier,
            $clientName,
            ArrayList::create([Grant::clientCredentials()]),
            ArrayList::create([Scope::publicApi()])
        );

        $this->clientPersister->persist($client);
    }

    private function assertSiteExists(InosaSiteIdentifier $inosaSiteIdentifier): void
    {
        if (!$this->apiClient->siteExists($inosaSiteIdentifier)) {
            throw new LogicException(
                sprintf('Inosa site %s does not exists', $inosaSiteIdentifier->toString())
            );
        }
    }

    private function alreadyHasClient(InosaSiteIdentifier $inosaSiteIdentifier): bool
    {
        return null !== $this->clientRepository->findOneByInosaSiteIdentifier($inosaSiteIdentifier);
    }
}
