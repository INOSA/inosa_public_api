<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientFactory;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientName;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Grant;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Domain\Scope\Scope;
use App\Shared\Infrastructure\Client\PublicApiClientPersister;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Inosa\Arrays\ArrayList;

final class CreatePublicApiClientDataFixtures extends Fixture
{
    public const CLIENT_INOSA_SITE_IDENTIFIER = '730ae434-e9bf-4153-8a3c-f165486cc2a4';
    public const CLIENT_INOSA_NAME = 'inosa';

    public function __construct(
        private PublicApiClientPersister $clientPersister,
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
                $this->identifierFactory->fromString(self::CLIENT_INOSA_SITE_IDENTIFIER),
            ),
            new ClientName(self::CLIENT_INOSA_NAME),
            ArrayList::create([Grant::clientCredentials()]),
            ArrayList::create([Scope::publicApi()])
        );

        $this->clientPersister->persist($client);
    }
}
