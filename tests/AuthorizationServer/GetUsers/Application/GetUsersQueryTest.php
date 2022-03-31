<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetUsers\Application;

use App\AuthorizationServer\GetUsers\Application\GetUsersQueryInterface;
use App\AuthorizationServer\GetUsers\Application\GetUsersRequest;
use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GetUsersQueryTest extends IntegrationTestCase
{
    private GetUsersQueryInterface $query;
    private MockHttpClient $httpClient;
    private JsonEncoderInterface $jsonEncoder;

    public function testGetUsersWithoutParametersWillReturnUsers(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => [
                                    'users_from_api',
                                ],
                                'errors' => null,
                            ],
                        ),
                    ),
                ),
            ],
        );

        $response = $this->query->getUsers(
            new GetUsersRequest(
                ArrayList::create([]),
                null,
                null,
                null,
                null,
            ),
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(200, $response->getStatusCode());
    }

    public function testGetUsersWithoutParametersWillReturnInternalServerException(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => null,
                                'errors' => [
                                    'message' => 'Internal server error from main api',
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

        $response = $this->query->getUsers(
            new GetUsersRequest(
                ArrayList::create([]),
                null,
                null,
                null,
                null,
            ),
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(500, $response->getStatusCode());
        $this::assertInstanceOf(InternalServerErrorView::class, $response);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->query = $container->get(GetUsersQueryInterface::class);
        $this->httpClient = $container->get(HttpClientInterface::class);
        $this->jsonEncoder = $container->get(JsonEncoderInterface::class);
    }
}
