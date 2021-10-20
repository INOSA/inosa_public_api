<?php

declare(strict_types=1);

namespace App\Shared\Domain\InosaApi;

use App\Shared\Domain\Identifier\InosaSiteIdentifier;

interface InosaApiInterface
{
    public function siteExists(InosaSiteIdentifier $inosaSiteIdentifier): bool;

    /**
     * @throws InosaApiException
     */
    public function createInosaPublicApiUser(InosaSiteIdentifier $inosaSiteIdentifier): void;
}
