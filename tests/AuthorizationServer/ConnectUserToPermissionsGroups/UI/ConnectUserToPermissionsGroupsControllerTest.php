<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\ConnectUserToPermissionsGroups\UI;

use App\Shared\Application\Json\JsonEncoderInterface;
use App\Tests\WebTestCase;
use Inosa\Arrays\ArrayHashMap;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

final class ConnectUserToPermissionsGroupsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private JsonEncoderInterface $jsonEncoder;

    public function testRequestIsSuccessful(): void
    {
        $this->client->request(
            method:  'PUT',
            uri:     'public-api/api/users/c93b442c-c43d-492f-a422-03c3dfb6345b/permissions-groups',
            server:  $this->getAuthorizationHeader(),
            content: '{"permissionsGroupsIds":["00bf6517-3982-4533-b670-8d911b81549c"]}'
        );

        self::assertResponseIsSuccessful();
    }

    public function testEmptyPermissionsGroupsArrayIsSuccessful(): void
    {
        $this->client->request(
            method:  'PUT',
            uri:     'public-api/api/users/012c25b6-1e63-40ae-9ebb-f3e894ec0150/permissions-groups',
            server:  $this->getAuthorizationHeader(),
            content: '{"permissionsGroupsIds":[]}'
        );

        self::assertResponseIsSuccessful();
    }

    public function testUserIdNotValidUuidReturnBadRequest(): void
    {
        $this->client->request(
            method:  'PUT',
            uri:     'public-api/api/users/this-is-some-kind-of-a-not-uuid/permissions-groups',
            server:  $this->getAuthorizationHeader(),
            content: '{"permissionsGroupsIds":["131d6443-5db6-43d9-b41b-18b3cc9f6ef5"]}'
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
            method:  'PUT',
            uri:     'public-api/api/users/this-is-some-kind-of-a-not-uuid/permissions-groups',
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
            'permissions-groups array with invalid uuid' => [
                [
                    'permissionsGroupsIds' => ['this-is-not-valid-uuid'],
                ],
            ],
            'no permissionsGroupsIds key' => [
                [],
            ],
            'permissions-groups contains one invalid uuid' => [
                [
                    'permissionsGroupsIds' => [
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
