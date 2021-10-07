<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Application\Query;

use App\Shared\Domain\Identifier\InosaSiteIdentifier;

interface FindPublicApiClientQueryInterface
{
    public function findByInosaSiteId(InosaSiteIdentifier $id): ?FindPublicApiClientCreateView;
}
