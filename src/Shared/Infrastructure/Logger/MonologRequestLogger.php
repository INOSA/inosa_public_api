<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Logger;

use App\Shared\Application\Json\JsonEncoderInterface;
use Inosa\Arrays\ArrayHashMap;
use LogicException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class MonologRequestLogger implements RequestLoggerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly JsonEncoderInterface $encoder,
    ) {
    }

    public function logRequest(ResponseInterface $response): void
    {
        $message = [
            'Request' => sprintf(
                '%s %s',
                $this->getHttpMethod($response),
                $this->getUrl($response),
            ),
            'Status code' => $response->getStatusCode(),
            'Headers' => $response->getHeaders(false),
            'Content' => $this->getArrayContent($response),
        ];

        $this->logger->info($this->encoder->encode(ArrayHashMap::create($message)));
    }

    /**
     * @return array<mixed>
     */
    private function getArrayContent(ResponseInterface $response): array
    {
        try {
            return $response->toArray(false);
        } catch (DecodingExceptionInterface) {
            return [];
        }
    }

    private function getHttpMethod(ResponseInterface $response): string
    {
        $method = $response->getInfo('http_method');

        if (false === is_string($method)) {
            throw new LogicException('Invalid HTTP method.');
        }

        return $method;
    }

    private function getUrl(ResponseInterface $response): string
    {
        $url = $response->getInfo('url');

        if (false === is_string($url)) {
            throw new LogicException('Invalid URL.');
        }

        return $url;
    }
}
