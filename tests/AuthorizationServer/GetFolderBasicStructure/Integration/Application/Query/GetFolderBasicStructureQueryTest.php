<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetFolderBasicStructure\Integration\Application\Query;

use App\AuthorizationServer\GetFoldersBasicStructure\Application\Query\GetFoldersBasicStructureQueryInterface;
use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayHashMap;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GetFolderBasicStructureQueryTest extends IntegrationTestCase
{
    private GetFoldersBasicStructureQueryInterface $query;
    private MockHttpClient $httpClient;
    private JsonEncoderInterface $jsonEncoder;

    public function testGetFolderBasicStructureWillReturnSuccessResponse(): void
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

        $response = $this->query->getFolderBasicStructureView();

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(200, $response->getStatusCode());
    }

    public function testGetFolderBasicStructureWillReturnInternalServerErrorResponse(): void
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

        $response = $this->query->getFolderBasicStructureView();

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(500, $response->getStatusCode());
        $this::assertInstanceOf(InternalServerErrorView::class, $response);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->httpClient = $container->get(HttpClientInterface::class);
        $this->query = $container->get(GetFoldersBasicStructureQueryInterface::class);
        $this->jsonEncoder = $container->get(JsonEncoderInterface::class);
    }
}
