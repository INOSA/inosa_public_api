<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Infrastructure;

use App\AuthorizationServer\GetFoldersBasicStructure\Domain\GetFolderBasicStructureHttpClientInterface;
use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Domain\ResponseCode;
use App\Shared\Domain\ResponseContent;
use App\Shared\Infrastructure\Http\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class GetFolderBasicStructureHttpClient implements GetFolderBasicStructureHttpClientInterface
{
    public function __construct(private HttpClient $httpClient)
    {
    }

    public function request(EndpointInterface $endpoint): ProxyResponse
    {
        $response = $this->httpClient->get($endpoint->getUrl());

        try {
            return new ProxyResponse(
                new ResponseContent($response->getContent()),
                new ResponseCode($response->getStatusCode()),
            );
        } catch (TransportExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $e) {
            return new ProxyResponse(
                new ResponseContent($e->getMessage()),
                ResponseCode::internalServerError()
            );
        }
    }
}
