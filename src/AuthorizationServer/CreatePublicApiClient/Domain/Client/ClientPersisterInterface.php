<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

interface ClientPersisterInterface
{
    /**
     * @throws ClientCreateException
     */
    public function persist(Client $client): void;
}
