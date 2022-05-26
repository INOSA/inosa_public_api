<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDocumentsPerStatus\UI;

use App\AuthorizationServer\GetDocumentsPerStatus\Application\GetDocumentsPerStatusRequest;
use App\AuthorizationServer\GetDocumentsPerStatus\Application\Query\GetDocumentsPerStatusQueryInterface;
use App\Shared\Domain\DocumentType;
use App\Shared\Domain\Identifier\FolderIdentifier;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class GetDocumentsPerStatusController extends ClientCredentialsAuthorizationController
{
    public function getDocumentsPerStatus(
        Request $request,
        GetDocumentsPerStatusQueryInterface $query,
        DenormalizerInterface $denormalizer,
        ResponseFactory $responseFactory,
        IdentifierFactoryInterface $identifierFactory,
    ): JsonResponse {
        /** @var GetDocumentsPerStatusRequest $documentsPerStatusRequest */
        $documentsPerStatusRequest = $denormalizer->denormalize(
            $request->query->all(),
            GetDocumentsPerStatusRequest::class,
        );

        $view = $query->getDocumentsPerStatus(
            ArrayList::create($documentsPerStatusRequest->documentTypes)->transform(
                fn(string $type): DocumentType => DocumentType::from($type)
            ),
            ArrayList::create($documentsPerStatusRequest->authorIds)->transform(
                fn(string $authorId): UserIdentifier => UserIdentifier::fromIdentifier(
                    $identifierFactory->fromString($authorId)
                )
            ),
            ArrayList::create($documentsPerStatusRequest->verifierIds)->transform(
                fn(string $authorId): UserIdentifier => UserIdentifier::fromIdentifier(
                    $identifierFactory->fromString($authorId)
                )
            ),
            ArrayList::create($documentsPerStatusRequest->approverIds)->transform(
                fn(string $authorId): UserIdentifier => UserIdentifier::fromIdentifier(
                    $identifierFactory->fromString($authorId)
                )
            ),
            ArrayList::create($documentsPerStatusRequest->folderIds)->transform(
                fn(string $authorId): FolderIdentifier => FolderIdentifier::fromIdentifier(
                    $identifierFactory->fromString($authorId)
                )
            ),
        );

        return $responseFactory->fromView($view);
    }
}
