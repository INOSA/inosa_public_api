<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Domain\InosaApi\InosaApiInterface;
use LogicException;

final class ClientCreator
{
    public function __construct(
        private InosaApiInterface $apiClient,
        private ClientSecretFactory $secretFactory,
        private ClientIdFactory $clientHashFactory,
        private ClientFactory $clientFactory,
        private ClientPersisterInterface $clientPersister,
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    /**
     * @throws ClientCreateException
     */
    public function create(InosaSiteIdentifier $inosaSiteIdentifier): void
    {
        $clientIdentifier = $inosaSiteIdentifier->toClientInternalIdentifier();

        $this->assertSiteExists($inosaSiteIdentifier);

        if ($this->alreadyHasClient($inosaSiteIdentifier)) {
            return;
        }

        $client = $this->clientFactory->create(
            $clientIdentifier,
            $inosaSiteIdentifier,
            $this->clientHashFactory->generate(),
            $this->secretFactory->generate()
        );

        $this->clientPersister->persist($client);
    }

    private function assertSiteExists(InosaSiteIdentifier $inosaSiteIdentifier): void
    {
        if (!$this->apiClient->siteExists($inosaSiteIdentifier)) {
            throw new LogicException(
                sprintf('Inosa site %s does not exists', $inosaSiteIdentifier->asString())
            );
        }
    }

    private function alreadyHasClient(InosaSiteIdentifier $inosaSiteIdentifier): bool
    {
        return null !== $this->clientRepository->findOneByInosaSiteIdentifier($inosaSiteIdentifier);
    }
}
