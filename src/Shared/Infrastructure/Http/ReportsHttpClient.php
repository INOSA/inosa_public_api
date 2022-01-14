<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

use App\Shared\Domain\Url\Url;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ReportsHttpClient
{
    private HttpClient $httpClient;

    public function __construct(
        private string $reportsApiUrl,
        private HttpClientInterface $apiClient,
        RequestStack $requestStack,
    ) {
        $this->httpClient = new HttpClient(
            $this->reportsApiUrl,
            $this->apiClient,
            $requestStack,
        );
    }

    public function get(Url $url): ResponseInterface
    {
        return $this->httpClient->get($url);
    }
}
