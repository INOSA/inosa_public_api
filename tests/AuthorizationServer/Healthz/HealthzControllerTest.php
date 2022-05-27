<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\Healthz;

use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

final class HealthzControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function testHealtzControllerWithWorkingApplicationIsSuccessful(): void
    {
        $this->client->request(
            method: 'GET',
            uri:    'public-api/healthz',
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
