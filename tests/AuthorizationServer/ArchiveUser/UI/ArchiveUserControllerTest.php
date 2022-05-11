<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\ArchiveUser\UI;

use App\Shared\Application\Json\JsonEncoderInterface;
use App\Tests\WebTestCase;
use Inosa\Arrays\ArrayHashMap;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

final class ArchiveUserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private JsonEncoderInterface $jsonEncoder;

    public function testPatchWithEmptyPayloadIsSuccessful(): void
    {
        $this->client->request(
            method:  'PATCH',
            uri:     'api/users/012c25b6-1e63-40ae-9ebb-f3e894ec0150/archive',
            server:  $this->getAuthorizationHeader(),
            content: '{}',
        );

        self::assertResponseIsSuccessful();
    }

    public function testPatchWithNotValidUserIdReturnBadRequest(): void
    {
        $this->client->request(
            method:  'PATCH',
            uri:     'api/users/this-is-some-kind-of-a-not-uuid/archive',
            server:  $this->getAuthorizationHeader(),
            content: '{}',
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param array<string, array<int, array<string, array<int, string>>>> $content
     * @dataProvider invalidContentDataProvider
     */
    public function testPatchWithInvalidUuidParamsReturnBadRequest(array $content): void
    {
        $this->client->request(
            method:  'PATCH',
            uri:     'api/users/82715858-bbb9-4b02-ac27-d81c07c9be3a/archive',
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
            'deviationsResponsibleId with invalid uuid' => [
                [
                    'deviationsResponsibleId' => ['this-is-not-valid-uuid'],
                ],
            ],
            'actionsResponsibleId with invalid uuid' => [
                [
                    'actionsResponsibleId' => ['this-is-not-valid-uuid'],
                ],
            ],
            'itemsResponsibleId with invalid uuid' => [
                [
                    'itemsResponsibleId' => ['this-is-not-valid-uuid'],
                ],
            ],
            'coSignerResponsibleId with invalid uuid' => [
                [
                    'coSignerResponsibleId' => ['this-is-not-valid-uuid'],
                ],
            ],
            'keepRoles with invalid uuid' => [
                [
                    'keepRoles' => ['this-is-for-sure-not-bool'],
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
