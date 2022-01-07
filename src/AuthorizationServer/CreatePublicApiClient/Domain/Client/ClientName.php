<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

final class ClientName
{
    public function __construct(private string $name)
    {
    }

    public function toString(): string
    {
        return $this->name;
    }
}
