<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Response;

use App\Shared\Domain\ProxyResponse;
use App\Shared\Domain\ResponseCode;
use App\Shared\Domain\ResponseContent;
use LogicException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ProxyResponseMapper
{
    private const THROW_RESPONSE_CONTENT_EXCEPTION = true;

    public function toProxyResponse(ResponseInterface $response): ProxyResponse
    {
        $responseCode = $this->getResponseCode($response);

        try {
            return new ProxyResponse(
                new ResponseContent($response->getContent(self::THROW_RESPONSE_CONTENT_EXCEPTION)),
                $responseCode
            );
        } catch (TransportExceptionInterface $e) {
            return new ProxyResponse(
                new ResponseContent($e->getMessage()),
                ResponseCode::internalServerError()
            );
        } catch (ClientExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
            return new ProxyResponse(
                new ResponseContent($e->getMessage()),
                $responseCode
            );
        }
    }

    private function getResponseCode(ResponseInterface $response): ResponseCode
    {
        try {
            return new ResponseCode($response->getStatusCode());
        } catch (TransportExceptionInterface $e) {
            throw new LogicException($e->getMessage());
        }
    }
}
