<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

use App\AuthorizationServer\CreatePublicApiClient\Domain\InosaApi\CreatePublicApiUserInosaApiInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
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
    public function create(InosaSiteIdentifier $inosaSiteIdentifier): void
    {
        $this->assertSiteExists($inosaSiteIdentifier);

        if ($this->alreadyHasClient($inosaSiteIdentifier)) {
            return;
        }

        $this->clientPersister->persist($this->clientFactory->create($inosaSiteIdentifier));
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
