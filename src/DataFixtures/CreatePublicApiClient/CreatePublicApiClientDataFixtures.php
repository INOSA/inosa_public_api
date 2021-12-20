<?php

declare(strict_types=1);

namespace App\DataFixtures\CreatePublicApiClient;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientFactory;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientPersisterInterface;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CreatePublicApiClientDataFixtures extends Fixture
{
    public const CLIENT_INOSA_SITE_IDENTIFIER = '730ae434-e9bf-4153-8a3c-f165486cc2a4';

    public function __construct(
        private ClientPersisterInterface $clientPersister,
        private ClientFactory $clientFactory,
        private IdentifierFactoryInterface $identifierFactory
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->createTestClient();
        $manager->flush();
    }

    private function createTestClient(): void
    {
        $client = $this->clientFactory->create(
            InosaSiteIdentifier::fromIdentifier(
                $this->identifierFactory->fromString(self::CLIENT_INOSA_SITE_IDENTIFIER)
            )
        );

        $this->clientPersister->persist($client);
    }
}
