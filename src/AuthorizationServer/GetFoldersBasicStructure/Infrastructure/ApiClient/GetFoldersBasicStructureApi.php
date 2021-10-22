<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Infrastructure\ApiClient;

use App\AuthorizationServer\GetFoldersBasicStructure\Application\Query\GetFoldersBasicStructureView;
use App\AuthorizationServer\GetFoldersBasicStructure\Domain\ApiClient\GetFoldersBasicStructureApiInterface;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Infrastructure\Http\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class GetFoldersBasicStructureApi implements GetFoldersBasicStructureApiInterface
{
    public function __construct(
        private HttpClient $httpClient,
        private ViewFactory $responseFactory,
    ) {
    }

    public function getFoldersBasicStructure(InosaSiteIdentifier $siteIdentifier): GetFoldersBasicStructureView|InternalServerErrorView
    {
        $responseData = $this->httpClient->get(
            sprintf('folders-basic-structure/without-metrics?siteId=%s', $siteIdentifier->asString()),
        );

        try {
            return new GetFoldersBasicStructureView($responseData->getContent(), $responseData->getStatusCode());
        } catch (TransportExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $e) {
            return $this->responseFactory->internalServerError();
        }
    }
}
