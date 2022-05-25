<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDocumentsPerStatus\Application;

final class GetDocumentsPerStatusRequest
{
    /**
     * @param array<string> $documentTypes
     * @param array<string> $authorIds
     * @param array<string> $verifierIds
     * @param array<string> $approverIds
     * @param array<string> $folderIds
     */
    public function __construct(
        public readonly array $documentTypes,
        public readonly array $authorIds,
        public readonly array $verifierIds,
        public readonly array $approverIds,
        public readonly array $folderIds,
    ) {
    }
}
