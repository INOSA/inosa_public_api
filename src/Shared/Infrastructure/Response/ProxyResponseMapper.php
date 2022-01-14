<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Response;

use App\Shared\Domain\ProxyResponse;
use App\Shared\Domain\ResponseCode;
use App\Shared\Domain\ResponseContent;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ProxyResponseMapper
{
    public function toProxyResponse(ResponseInterface $response): ProxyResponse
    {
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
