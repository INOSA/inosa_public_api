<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetDepartments\UI;

use App\Tests\WebTestCase;

final class GetDepartmentsControllerTest extends WebTestCase
{
    public function testGetDepartmentsShouldBeOk(): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri: 'api/departments',
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseIsSuccessful();
    }
}
