<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Query;

use App\AuthorizationServer\CreatePublicApiClient\Application\Query\FindPublicApiClientCreateView;
use App\AuthorizationServer\CreatePublicApiClient\Application\Query\FindPublicApiClientQueryInterface;
use App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Repository\ClientRepository;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

final class FindPublicApiClientQuery implements FindPublicApiClientQueryInterface
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public function findByInosaSiteId(InosaSiteIdentifier $id): ?FindPublicApiClientCreateView
    {
        $result = $this->clientRepository->findOneByInosaSiteIdentifier($id);

        if (null === $result) {
            return null;
        }

        return new FindPublicApiClientCreateView(
            $result->getClientId()->toString(),
            $result->getClientSecret()->toString()
        );
    }
}
