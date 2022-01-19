<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetDepartments\Application;

use App\AuthorizationServer\GetDepartments\Application\GetDepartmentsQueryInterface;
use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayHashMap;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GetDepartmentsQueryTest extends IntegrationTestCase
{
    private GetDepartmentsQueryInterface $query;
    private MockHttpClient $httpClient;
    private JsonEncoderInterface $jsonEncoder;

    public function testGetDepartmentsWillReturnInternalServerErrorResponse(): void
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

        $response = $this->query->getDepartments(
            new InosaSiteIdentifier('f6be1c12-4bb7-4ab1-9c2b-454347aee2a1')
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(500, $response->getStatusCode());
        $this::assertInstanceOf(InternalServerErrorView::class, $response);
    }

    public function testGetDepartmentsShouldReturnSuccessfulStatusCode(): void
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

        $response = $this->query->getDepartments(
            new InosaSiteIdentifier('f6be1c12-4bb7-4ab1-9c2b-454347aee2a1')
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(200, $response->getStatusCode());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->httpClient = $container->get(HttpClientInterface::class);
        $this->query = $container->get(GetDepartmentsQueryInterface::class);
        $this->jsonEncoder = $container->get(JsonEncoderInterface::class);
    }
}
