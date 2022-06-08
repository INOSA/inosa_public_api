<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDocumentsPerStatus\Domain;

use App\Shared\Domain\DocumentType;
use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\Identifier\FolderIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;

final class GetDocumentsPerStatusEndpoint implements PostEndpointInterface
{
    private const URL = 'reports/documents/document-status';

    /**
     * @param ArrayList<DocumentType> $documentTypes
     * @param ArrayList<UserIdentifier> $authorIds
     * @param ArrayList<UserIdentifier> $verifierIds
     * @param ArrayList<UserIdentifier> $approverIds
     * @param ArrayList<FolderIdentifier> $folderIds
     */
    public function __construct(
        private readonly ArrayList $documentTypes,
        private readonly ArrayList $authorIds,
        private readonly ArrayList $verifierIds,
        private readonly ArrayList $approverIds,
        private readonly ArrayList $folderIds,
    ) {
    }

    public function getUrl(): Url
    {
        return new Url(self::URL);
    }

    /**
     * @inheritDoc
     */
    public function getParams(): ArrayHashMap
    {
        return ArrayHashMap::create(
            [
                'globalFilters' => $this->getGlobalFilters()->toArray(),
                'localFilters' => $this->getLocalFilters()->toArray(),
            ]
        );
    }

    /**
     * @return ArrayHashMap<array<string, string>>
     */
    private function getGlobalFilters(): ArrayHashMap
    {
        if (true === $this->folderIds->isEmpty()) {
            return ArrayHashMap::empty();
        }

        return ArrayHashMap::create(
            [
                'folderIds' => $this->folderIds
                    ->transform(fn(FolderIdentifier $identifier): string => $identifier->toString())
                    ->toArray(),
            ]
        );
    }

    /**
     * @return ArrayHashMap<array<string, string>>
     */
    private function getLocalFilters(): ArrayHashMap
    {
        $localFilters = ArrayHashMap::create([]);

        if (false === $this->documentTypes->isEmpty()) {
            $localFilters->put(
                'documentTypes',
                $this->documentTypes
                    ->transform(fn(DocumentType $type): string => $type->value)
                    ->toArray(),
            );
        }

        if (false === $this->authorIds->isEmpty()) {
            $localFilters->put(
                'authorIds',
                $this->authorIds
                    ->transform(fn(UserIdentifier $identifier): string => $identifier->toString())
                    ->toArray(),
            );
        }

        if (false === $this->verifierIds->isEmpty()) {
            $localFilters->put(
                'verifierIds',
                $this->verifierIds
                    ->transform(fn(UserIdentifier $identifier): string => $identifier->toString())
                    ->toArray(),
            );
        }

        if (false === $this->approverIds->isEmpty()) {
            $localFilters->put(
                'approverIds',
                $this->approverIds
                    ->transform(fn(UserIdentifier $identifier): string => $identifier->toString())
                    ->toArray(),
            );
        }

        return $localFilters;
    }
}
