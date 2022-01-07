<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusForOneUser\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\Identifier\DocumentIdentifier;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;

final class GetReadingStatusForOneUserEndpoint implements PostEndpointInterface
{
    private const URL = 'reports/competence/user-reading';

    /**
     * @param ArrayList<RoleIdentifier> $roleIds
     * @param ArrayList<DocumentIdentifier> $documentIds
     */
    public function __construct(
        private UserIdentifier $userIdentifier,
        private ArrayList $roleIds,
        private ArrayList $documentIds
    ) {
    }

    public function getUrl(): Url
    {
        return new Url(sprintf('%s', self::URL));
    }

    /**
     * @inheritDoc
     */
    public function getParams(): ArrayHashMap
    {
        return ArrayHashMap::create(
            [
                'globalFilters' => [],
                'localFilters' => [
                    'documentIds' => $this
                        ->documentIds
                        ->transform(fn(DocumentIdentifier $identifier): string => $identifier->toString())
                        ->toArray(),
                    'roleIds' => $this
                        ->roleIds
                        ->transform(fn(RoleIdentifier $identifier): string => $identifier->toString())
                        ->toArray(),
                    'userId' => $this
                        ->userIdentifier
                        ->toString(),
                ],
                'pagination' => [
                    'page' => 0,
                    'size' => -1,
                ],
            ]
        );
    }
}
