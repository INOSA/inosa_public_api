<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetRoles\UI;

use App\Tests\WebTestCase;

final class GetRolesControllerTest extends WebTestCase
{
    public function testGetRolesResponseIsSuccess(): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri: 'public-api/api/roles',
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseIsSuccessful();
    }
}
