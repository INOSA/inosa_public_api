<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetDocumentsPerStatus\Application;

use App\AuthorizationServer\GetDocumentsPerStatus\Application\Query\GetDocumentsPerStatusQueryInterface;
use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GetDocumentsPerStatusQueryTest extends IntegrationTestCase
{
    private GetDocumentsPerStatusQueryInterface $query;
    private MockHttpClient $httpClient;
    private JsonEncoderInterface $jsonEncoder;

    public function testGetDocumentsPerStatusWillReturnInternalServerErrorResponse(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => null,
                                'errors' => [
                                    'exception' => 'Internal server error.',
                                ],
                            ],
                        )
                    ),
                    [
                        'http_code' => 500,
                    ],
                ),
            ],
        );

        $response = $this->query->getDocumentsPerStatus(
            ArrayList::empty(),
            ArrayList::empty(),
            ArrayList::empty(),
            ArrayList::empty(),
            ArrayList::empty(),
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(500, $response->getStatusCode());
        $this::assertInstanceOf(InternalServerErrorView::class, $response);
    }

    public function testGetDocumentsPerStatusShouldReturnSuccessfulStatusCode(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => [
                                    'response_from_api',
                                ],
                                'errors' => null,
                            ],
                        )
                    )
                ),
            ],
        );

        $response = $this->query->getDocumentsPerStatus(
            ArrayList::empty(),
            ArrayList::empty(),
            ArrayList::empty(),
            ArrayList::empty(),
            ArrayList::empty(),
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(200, $response->getStatusCode());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->httpClient = $container->get(HttpClientInterface::class);
        $this->query = $container->get(GetDocumentsPerStatusQueryInterface::class);
        $this->jsonEncoder = $container->get(JsonEncoderInterface::class);
    }
}
