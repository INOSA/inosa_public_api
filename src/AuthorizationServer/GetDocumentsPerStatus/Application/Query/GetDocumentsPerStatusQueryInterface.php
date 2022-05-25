<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDocumentsPerStatus\Application\Query;

use App\Shared\Application\Query\ResponseViewInterface;
use App\Shared\Domain\DocumentType;
use App\Shared\Domain\Identifier\FolderIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use Inosa\Arrays\ArrayList;

interface GetDocumentsPerStatusQueryInterface
{
    /**
     * @param ArrayList<DocumentType> $documentTypes
     * @param ArrayList<UserIdentifier> $authorIds
     * @param ArrayList<UserIdentifier> $verifierIds
     * @param ArrayList<UserIdentifier> $approverIds
     * @param ArrayList<FolderIdentifier> $folderIds
     */
    public function getDocumentsPerStatus(
        ArrayList $documentTypes,
        ArrayList $authorIds,
        ArrayList $verifierIds,
        ArrayList $approverIds,
        ArrayList $folderIds,
    ): ResponseViewInterface;
}
