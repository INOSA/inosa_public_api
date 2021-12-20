<?php

declare(strict_types=1);

namespace App\Tests;

use App\Shared\Infrastructure\Test\AccessTokenIssuer;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;

abstract class WebTestCase extends SymfonyWebTestCase
{
    public function getClient(): KernelBrowser
    {
        return self::createClient();
    }

    /**
     * @return string[]
     */
    public function getAuthorizationHeader(): array
    {
        return [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->issueTestAccessToken()),
        ];
    }

    private function issueTestAccessToken(): string
    {
        $container = static::getContainer();

        return $container->get(AccessTokenIssuer::class)->issueTestAccessToken();
    }
}
