<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\DeleteUser\UI;

use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

final class DeleteUserControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function testDeleteWithNoQueryParamsIsSuccessful(): void
    {
        $this->client->request(
            method: 'DELETE',
            uri:    'public-api/api/users/012c25b6-1e63-40ae-9ebb-f3e894ec0150',
            server: $this->getAuthorizationHeader(),
        );

        self::assertResponseIsSuccessful();
    }

    public function testDeleteWithQueryParamsIsSuccessful(): void
    {
        $this->client->request(
            method: 'DELETE',
            uri:    'public-api/api/users/012c25b6-1e63-40ae-9ebb-f3e894ec0150?itemsResponsibleId=fa2880ec-9f95-4fa0-a06e-a07e7baa5371&coSignerResponsibleId=9163e66d-0c1d-4eb7-bd12-64afca10f9d1',
            server: $this->getAuthorizationHeader(),
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider invalidIdentifierProvider
     */
    public function testPatchWithInvalidItemsResponsibleIdReturnBadRequest(string $itemsResponsibleId): void
    {
        $url = sprintf(
            '%s?itemsResponsibleId=%s',
            'public-api/api/users/82715858-bbb9-4b02-ac27-d81c07c9be3a',
            $itemsResponsibleId
        );

        $this->client->request(
            method: 'DELETE',
            uri:    $url,
            server: $this->getAuthorizationHeader(),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidIdentifierProvider
     */
    public function testDeleteWithInvalidCoSignerResponsibleIdReturnBadRequest(string $invalidCoSignerResponsibleId): void
    {
        $url = sprintf(
            '%s%s',
            'public-api/api/users/82715858-bbb9-4b02-ac27-d81c07c9be3a?coSignerResponsibleId=',
            $invalidCoSignerResponsibleId,
        );

        $this->client->request(
            method: 'DELETE',
            uri:    $url,
            server: $this->getAuthorizationHeader(),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function invalidIdentifierProvider(): array
    {
        return [
            'empty string' => [
                '',
            ],
            'invalid uuid' => [
                'this-is-not-valid-uuid',
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->getClient();
    }
}
