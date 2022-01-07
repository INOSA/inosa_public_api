<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class HttpClient
{
    public function __construct(
        private string $apiUrl,
        private HttpClientInterface $apiClient,
        private RequestStack $requestStack,
    ) {
    }

    public function get(Url $url): ResponseInterface
    {
        $parsedUrl = sprintf(
            '%s/%s',
            $this->apiUrl,
            $url->toString(),
        );

        return $this->apiClient->request(
            'GET',
            $parsedUrl,
            [
                'headers' => $this->getHeaders()->toArray(),
            ]
        );
    }

    /**
     * @param ArrayHashMap<mixed> $body
     */
    public function post(Url $url, ArrayHashMap $body): ResponseInterface
    {
        return $this->apiClient->request(
            'POST',
            sprintf('%s/%s', $this->apiUrl, $url->toString()),
            [
                'headers' => $this->getHeaders()->toArray(),
                'json' => $body->toArray(),
            ]
        );
    }

    /**
     * @param ArrayHashMap<mixed> $body
     */
    public function put(Url $url, ArrayHashMap $body): ResponseInterface
    {
        return $this->apiClient->request(
            'PUT',
            sprintf('%s/%s', $this->apiUrl, $url->toString()),
            [
                'headers' => $this->getHeaders()->toArray(),
                'json' => $body->toArray(),
            ]
        );
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
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return null;
        }

        return $request->headers->get('Authorization');
    }
}
