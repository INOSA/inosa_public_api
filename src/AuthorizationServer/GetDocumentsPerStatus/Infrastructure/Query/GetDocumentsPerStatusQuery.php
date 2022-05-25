<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDocumentsPerStatus\Infrastructure\Query;

use App\AuthorizationServer\GetDocumentsPerStatus\Application\Query\GetDocumentsPerStatusQueryInterface;
use App\AuthorizationServer\GetDocumentsPerStatus\Domain\GetDocumentsPerStatusApi;
use App\AuthorizationServer\GetDocumentsPerStatus\Domain\GetDocumentsPerStatusEndpoint;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\ResponseViewInterface;
use Inosa\Arrays\ArrayList;

final class GetDocumentsPerStatusQuery implements GetDocumentsPerStatusQueryInterface
{
    public function __construct(
        private readonly GetDocumentsPerStatusApi $api,
        private readonly ViewFactory $viewFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getDocumentsPerStatus(ArrayList $documentTypes, ArrayList $authorIds, ArrayList $verifierIds, ArrayList $approverIds, ArrayList $folderIds,): ResponseViewInterface
    {
        $proxyResponse = $this->api->request(
            new GetDocumentsPerStatusEndpoint(
                $documentTypes,
                $authorIds,
                $verifierIds,
                $approverIds,
                $folderIds,
            )
        );

        return $this->viewFactory->fromProxyResponse($proxyResponse);
    }
}
