<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetFolderBasicStructure\Smoke\UI;

use App\Tests\WebTestCase;

final class GetFolderBasicStructureControllerTest extends WebTestCase
{
    public function testGetFolderBasicStructure(): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri: 'api/folders-basic-structure/without-metrics',
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseIsSuccessful();
    }
}
