<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetFolderBasicStructure\Smoke\UI;

use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

final class GetFolderBasicStructureControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function testGetFolderBasicStructure(): void
    {
        $this->client->request(
            method: 'GET',
            uri:    'public-api/api/folders-basic-structure/without-metrics',
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseIsSuccessful();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->getClient();
    }
}
