<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\ConnectUserToRoles\UI;

use App\Shared\Application\Json\JsonEncoderInterface;
use App\Tests\WebTestCase;
use Inosa\Arrays\ArrayHashMap;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

final class ConnectUserToRolesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private JsonEncoderInterface $jsonEncoder;

    public function testRequestIsSuccessful(): void
    {
        $this->client->request(
            method:  'PATCH',
            uri:     'api/users/012c25b6-1e63-40ae-9ebb-f3e894ec0150/roles',
            server:  $this->getAuthorizationHeader(),
            content: '{"roles":["9a66a3e9-280f-4c94-ae35-3446c242d77f"]}'
        );

        self::assertResponseIsSuccessful();
    }

    public function testUserIdNotValidUuidReturnBadRequest(): void
    {
        $this->client->request(
            method:  'PATCH',
            uri:     'api/users/this-is-some-kind-of-a-not-uuid/roles',
            server:  $this->getAuthorizationHeader(),
            content: '{"roles":["9a66a3e9-280f-4c94-ae35-3446c242d77f"]}'
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param array<string, array<int, array<string, array<int, string>>>> $content
     * @dataProvider invalidContentDataProvider
     */
    public function testReturnBadRequestOnInvalidContent(array $content): void
    {
        $this->client->request(
            method:  'PATCH',
            uri:     'api/users/this-is-some-kind-of-a-not-uuid/roles',
            server:  $this->getAuthorizationHeader(),
            content: $this->jsonEncoder->encode(ArrayHashMap::create($content)),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return array<string, array<int, array<string, array<int, string>>>>
     */
    public function invalidContentDataProvider(): array
    {
        return [
            'empty roles array' => [
                [
                    'roles' => [],
                ],
            ],
            'roles array with invalid uuid' => [
                [
                    'roles' => ['this-is-not-valid-uuid'],
                ],
            ],
            'no roles key' => [
                [],
            ],
            'roles contains one invalid uuid' => [
                [
                    'roles' => [
                        '87d67fb4-85da-45fa-9d50-4107771ee676',
                        'this-is-not-valid-uuid',
                    ],
                ],
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->getClient();
        $this->jsonEncoder = self::getContainer()->get(JsonEncoderInterface::class);
    }
}
