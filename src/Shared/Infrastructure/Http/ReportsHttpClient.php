<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ReportsHttpClient
{
    private readonly HttpClient $httpClient;

    public function __construct(
        string $reportsApiUrl,
        readonly HttpClientInterface $apiClient,
        readonly RequestStack $requestStack,
    ) {
        $this->httpClient = new HttpClient(
            $reportsApiUrl,
            $apiClient,
            $requestStack,
        );
    }

    public function get(Url $url): ResponseInterface
    {
        return $this->httpClient->get($url);
    }

    /**
     * @param ArrayHashMap<mixed> $params
     */
    public function post(Url $url, ArrayHashMap $params): ResponseInterface
    {
        return $this->httpClient->post($url, $params);
    }
}
