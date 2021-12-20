<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class HttpClient
{
    public function __construct(
        private string $apiUrl,
        private HttpClientInterface $apiClient,
    ) {
    }

    public function get(Url $url): ResponseInterface
    {
        $parsedUrl = sprintf(
            '%s/%s',
            $this->apiUrl,
            $url->asString(),
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
            sprintf('%s/%s', $this->apiUrl, $url->asString()),
            [
                'headers' => $this->getHeaders()->toArray(),
                'body' => $body->toArray(),
            ]
        );
    }

    /**
     * @return ArrayHashMap<string>
     */
    private function getHeaders(): ArrayHashMap
    {
        return ArrayHashMap::create(
            [
                'Accept' => 'application/json',
                'Origin' => 'public-api',
            ]
        );
    }
}
