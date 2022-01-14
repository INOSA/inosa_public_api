<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Domain\Scope\Scope;
use Inosa\Arrays\ArrayList;

final class Client
{
    /**
     * @param ArrayList<Grant> $grants
     * @param ArrayList<Scope> $scopes
     */
    public function __construct(
        private ClientInternalIdentifier $clientInternalIdentifier,
        private InosaSiteIdentifier $siteId,
        private ClientName $clientName,
        private ClientIdentifier $clientId,
        private ClientSecret $clientSecret,
        private ArrayList $grants,
        private ArrayList $scopes,
    ) {
    }

    public function getClientInternalIdentifier(): ClientInternalIdentifier
    {
        return $this->clientInternalIdentifier;
    }

    public function getSiteId(): InosaSiteIdentifier
    {
        return $this->siteId;
    }

    public function getClientId(): ClientIdentifier
    {
        return $this->clientId;
    }

    public function getClientSecret(): ClientSecret
    {
        return $this->clientSecret;
    }

    public function getClientName(): ClientName
    {
        return $this->clientName;
    }

    /**
     * @return ArrayList<Grant>
     */
    public function getGrants(): ArrayList
    {
        return $this->grants;
    }

    /**
     * @return ArrayList<Scope>
     */
    public function getScopes(): ArrayList
    {
        return $this->scopes;
    }
}
