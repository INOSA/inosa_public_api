<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\Response\ProxyResponseMapper;

use App\Shared\Infrastructure\Response\ProxyResponseMapper;
use App\Tests\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ProxyResponseMapperTest extends UnitTestCase
{
    private ProxyResponseMapper $mapper;

    /**
     * @var MockObject|ResponseInterface $responseMock
     */
    private MockObject $responseMock;

    /**
     * @dataProvider responseMapperDataProvider
     */
    public function testShouldReturnCorrectResponse(
        string $content,
        int $code,
        string $expectedContent,
        int $expectedStatusCode,
    ): void {
        $this->responseMock->method('getStatusCode')->willReturn($code);
        $this->responseMock->method('getContent')->willReturn($content);

        $mappedResponse = $this->mapper->toProxyResponse($this->responseMock);

        self::assertEquals($expectedStatusCode, $mappedResponse->getResponseCode()->asInt());
        self::assertEquals($expectedContent, $mappedResponse->getResponseContent()->toString());
    }

    /**
     * @return array<string, array<int, int|string>>
     */
    public function responseMapperDataProvider(): array
    {
        return [
            '200 with content' => [
                '{"message":"Success"}',
                200,
                '{"message":"Success"}',
                200,
            ],
            '201 with no content' => [
                '',
                201,
                '',
                201,
            ],
            '400 with validation error' => [
                'Client id is already taken',
                400,
                'Client id is already taken',
                400,
            ],
            '500 with content' => [
                'Really bad sqlincjection response from main api',
                500,
                'Internal server error, please contact with the Administrator.',
                500,
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new ProxyResponseMapper();
        $this->responseMock = $this->createMock(ResponseInterface::class);
    }
}
