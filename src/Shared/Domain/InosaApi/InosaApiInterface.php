<?php

declare(strict_types=1);

namespace App\Shared\Domain\InosaApi;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientInternalIdentifier;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

interface InosaApiInterface
{
    public function siteExists(InosaSiteIdentifier $inosaSiteIdentifier): bool;

    /**
     * @throws InosaApiException
     */
    public function createInosaPublicApiUser(ClientInternalIdentifier $clientIdentifier): void;
}
