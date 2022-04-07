<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Application\Query;

final class FindPublicApiClientCreateView
{
    public function __construct(private string $clientId, private string $clientSecret)
    {
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }
}
