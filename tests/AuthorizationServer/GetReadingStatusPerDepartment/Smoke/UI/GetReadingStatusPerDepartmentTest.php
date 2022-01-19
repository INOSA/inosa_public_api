<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetReadingStatusPerDepartment\Smoke\UI;

use App\Tests\WebTestCase;

final class GetReadingStatusPerDepartmentTest extends WebTestCase
{
    public function testGetReadingStatusPerDepartmentSmokeTestReturnSuccess(): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri: 'api/departments/reading-status',
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseIsSuccessful();
    }
}
