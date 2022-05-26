<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetDocumentsPerStatus\UI;

use App\Shared\Domain\DocumentType;
use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetDocumentsPerStatusTest extends WebTestCase
{
    public function testGetDocumentsPerStatusShouldBeOk(): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri:    'api/documents/status',
            server: $this->getAuthorizationHeader(),
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider documentTypeDataProvider
     */
    public function testGetDocumentsPerStatusDocumentTypeParameters(string $documentType, int $expectedStatusCode): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri:    sprintf('api/documents/status?documentType[]=%s', $documentType),
            server: $this->getAuthorizationHeader(),
        );

        self::assertResponseStatusCodeSame($expectedStatusCode);
    }

    /**
     * @return array<string, array<string|int>>
     */
    public function documentTypeDataProvider(): array
    {
        return [
            'document type inosa' => [
                DocumentType::INOSA->value,
                200,
            ],
            'document type url' => [
                DocumentType::URL->value,
                200,
            ],
            'document type quiz' => [
                DocumentType::QUIZ->value,
                200,
            ],
            'document type message' => [
                DocumentType::MESSAGE->value,
                200,
            ],
            'document type file' => [
                DocumentType::FILE->value,
                200,
            ],
            'not existing document type' => [
                'NOTEXISTING',
                400,
            ],
        ];
    }

    public function testGetDocumentsPerStatusWithValidParametersResultInSuccess(): void
    {
        $uri = sprintf(
            'api/documents/status?documentType[]=%s&authorId[]=%s&verifierId[]=%s&approverId[]=%s&folderId[]=%s',
            DocumentType::FILE->value,
            'a3e07bc8-13a7-48a5-8ccb-2aeff6bce9a0',
            'c479b02a-a9a9-4620-addb-346c2e5635e5',
            '18b87f58-22c2-4bf6-ace4-50af7de144fa',
            '5952f97b-0be0-479b-a204-127f2e5b5183',
        );

        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri:    $uri,
            server: $this->getAuthorizationHeader(),
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider invalidParametersDataProvider
     */
    public function testGetDocumentsPerStatusWithInvalidParametersResultInBadRequest(string $parameters): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri:    sprintf('api/documents/status%s', $parameters),
            server: $this->getAuthorizationHeader(),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function invalidParametersDataProvider(): array
    {
        return [
            'documentType is not an array' => [
                '?documentType=abc',
            ],
            'documentType is an array with invalid uuid' => [
                '?documentType=this-is-not-valid-uuid',
            ],
            'authorId is not an array' => [
                '?authorId=abc',
            ],
            'authorId is an array with invalid uuid' => [
                '?authorId=this-is-not-valid-uuid',
            ],
            'verifierId is not an array' => [
                '?verifierId=abc',
            ],
            'verifierId is an array with invalid uuid' => [
                '?verifierId=this-is-not-valid-uuid',
            ],
            'approverId is not an array' => [
                '?approverId=abc',
            ],
            'approverId is an array with invalid uuid' => [
                '?verifierId=this-is-not-valid-uuid',
            ],
            'folderId is not an array' => [
                '?folderId=abc',
            ],
            'folderId is an array with invalid uuid' => [
                '?folderId=this-is-not-valid-uuid',
            ],
            'all parameters not array is an array with invalid uuid' => [
                '?documentType=abc&authorId=abc&verifierId=abc&approverId=abc&folderId=abc',
            ],
        ];
    }
}
