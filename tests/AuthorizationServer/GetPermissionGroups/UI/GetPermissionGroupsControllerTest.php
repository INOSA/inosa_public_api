<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetRoles\UI;

use App\Tests\WebTestCase;

final class GetPermissionGroupsControllerTest extends WebTestCase
{
    public function testGetPermissionGroupsResponseIsSuccess(): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri: 'api/permission-groups',
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseIsSuccessful();
    }
}
