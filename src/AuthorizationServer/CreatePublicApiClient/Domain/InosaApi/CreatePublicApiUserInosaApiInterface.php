<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\InosaApi;

use App\Shared\Domain\Identifier\InosaSiteIdentifier;

interface CreatePublicApiUserInosaApiInterface
{
    public function siteExists(InosaSiteIdentifier $inosaSiteIdentifier): bool;

    /**
     * @throws CreatePublicApiUserInosaApiException
     */
    public function createInosaPublicApiUser(InosaSiteIdentifier $inosaSiteIdentifier): void;
}
