<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Response;

use App\Shared\Domain\ProxyResponse;
use App\Shared\Domain\ResponseCode;
use App\Shared\Domain\ResponseContent;
use LogicException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ProxyResponseMapper
{
    private const THROW_RESPONSE_CONTENT_EXCEPTION = false;

    public function toProxyResponse(ResponseInterface $response): ProxyResponse
    {
        $responseCode = $this->getResponseCode($response);
        $content = $this->getResponseContent($response);
        if ($responseCode->isServerError()) {
            return ProxyResponse::internalServerError();
        }

        return new ProxyResponse($this->getResponseContent($response), $responseCode);
    }

    private function getResponseCode(ResponseInterface $response): ResponseCode
    {
        try {
            return new ResponseCode($response->getStatusCode());
        } catch (ExceptionInterface $e) {
            throw new LogicException($e->getMessage());
        }
    }

    private function getResponseContent(ResponseInterface $response): ResponseContent
    {
        try {
            return new ResponseContent($response->getContent(self::THROW_RESPONSE_CONTENT_EXCEPTION));
        } catch (ExceptionInterface $e) {
            throw new LogicException($e->getMessage());
        }
    }
}
