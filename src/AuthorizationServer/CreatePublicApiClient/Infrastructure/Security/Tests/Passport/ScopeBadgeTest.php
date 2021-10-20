<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Security\Tests\Passport;

use App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Security\Passport\ScopeBadge;
use App\Tests\UnitTest;

final class ScopeBadgeTest extends UnitTest
{
    public function testInstantiation(): void
    {
        $scopes = [
            'scope1',
            'scope2',
            'scope3',
            'scope4'
        ];

        $scopeBadge = new ScopeBadge($scopes);

        $this::assertInstanceOf(ScopeBadge::class, $scopeBadge);
    }
}
