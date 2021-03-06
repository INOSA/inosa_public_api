<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\CreatePublicApiClient\Unit\Infrastructure\Security\Passport;

use App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Security\Passport\ScopeBadge;
use App\Tests\UnitTestCase;

final class ScopeBadgeTest extends UnitTestCase
{
    public function testInstantiation(): void
    {
        $scopes = [
            'scope1',
            'scope2',
            'scope3',
            'scope4',
        ];

        $scopeBadge = new ScopeBadge($scopes);

        $this::assertEquals(ScopeBadge::class, $scopeBadge::class);
    }
}
