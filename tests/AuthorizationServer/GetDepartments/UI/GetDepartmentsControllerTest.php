<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetDepartments\UI;

use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

final class GetDepartmentsControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function testGetDepartmentsShouldBeOk(): void
    {
        $this->client->request(
            method: 'GET',
            uri:    'public-api/api/departments',
            server: $this->getAuthorizationHeader(),
        );

        self::assertResponseIsSuccessful();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->getClient();
    }
}
