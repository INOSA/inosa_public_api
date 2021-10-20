<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Domain\Client;

use App\Shared\Domain\Identifier\InosaSiteIdentifier;

interface ClientRepositoryInterface
{
    public function findOneByInosaSiteIdentifier(InosaSiteIdentifier $id): ?Client;

    /**
     * @throws ClientRepositoryException
     */
    public function save(Client $client): void;
}
