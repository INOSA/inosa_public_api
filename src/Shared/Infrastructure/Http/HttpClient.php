<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

use App\Shared\Domain\Url\Url;
use App\Shared\Infrastructure\Logger\RequestLoggerInterface;
use Inosa\Arrays\ArrayHashMap;
use LogicException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class HttpClient
{
    public function __construct(
        private readonly string $apiUrl,
        private readonly HttpClientInterface $apiClient,
        private readonly RequestStack $requestStack,
        private readonly RequestLoggerInterface $logger,
    ) {
    }

    /**
     * @param ArrayHashMap<mixed> $body
     */
    public function delete(Url $url, ArrayHashMap $body): ResponseInterface
    {
        try {
            $response = $this->apiClient->request(
                'DELETE',
                sprintf('%s/%s', $this->apiUrl, $url->toString()),
                [
                    'headers' => $this->getHeaders()->toArray(),
                    'json' => $body->toArray(),
                ]
            );
        } catch (TransportExceptionInterface $e) {
            throw new LogicException($e->getMessage());
        }

        $this->logger->logRequest($response);

        return $response;
    }

    public function get(Url $url): ResponseInterface
    {
        try {
            $response = $this->apiClient->request(
                'GET',
                sprintf('%s/%s', $this->apiUrl, $url->toString()),
                [
                    'headers' => $this->getHeaders()->toArray(),
                ]
            );
        } catch (TransportExceptionInterface $e) {
            throw new LogicException($e->getMessage());
        }

        $this->logger->logRequest($response);

        return $response;
    }

    /**
     * @param ArrayHashMap<mixed> $body
     */
    public function post(Url $url, ArrayHashMap $body): ResponseInterface
    {
        try {
            $response = $this->apiClient->request(
                'POST',
                sprintf('%s/%s', $this->apiUrl, $url->toString()),
                [
                    'headers' => $this->getHeaders()->toArray(),
                    'json' => $body->toArray(),
                ]
            );
        } catch (TransportExceptionInterface $e) {
            throw new LogicException($e->getMessage());
        }

        $this->logger->logRequest($response);

        return $response;
    }

    /**
     * @param ArrayHashMap<mixed> $body
     */
    public function put(Url $url, ArrayHashMap $body): ResponseInterface
    {
        try {
            $response = $this->apiClient->request(
                'PUT',
                sprintf('%s/%s', $this->apiUrl, $url->toString()),
                [
                    'headers' => $this->getHeaders()->toArray(),
                    'json' => $body->toArray(),
                ]
            );
        } catch (TransportExceptionInterface $e) {
            throw new LogicException($e->getMessage());
        }

        $this->logger->logRequest($response);

        return $response;
    }

    /**
     * @return ArrayHashMap<string>
     */
    private function getHeaders(): ArrayHashMap
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Origin' => 'public-api',
        ];

        $authorization = $this->getAuthorizationHeader();

        if (null === $authorization) {
            return ArrayHashMap::create($headers);
        }

        $headers['Authorization'] = $authorization;

        return ArrayHashMap::create($headers);
    }

    private function getAuthorizationHeader(): ?string
    {
        return $this->requestStack->getCurrentRequest()?->headers->get('Authorization');
    }
}
