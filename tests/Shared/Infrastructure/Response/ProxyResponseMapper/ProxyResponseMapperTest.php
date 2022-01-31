<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\Response\ProxyResponseMapper;

use App\Shared\Infrastructure\Response\ProxyResponseMapper;
use App\Tests\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

final class ProxyResponseMapperTest extends UnitTestCase
{
    private ProxyResponseMapper $mapper;

    /**
     * @var MockObject|ResponseInterface $responseMock
     */
    private MockObject $responseMock;

    /**
     * @return array<string, array<int, MockObject|\Throwable|int>>
     */
    public function getMockedException(): array
    {
        return [
            'Transport exception, 500' => [
                $this->createMock(TransportExceptionInterface::class),
                500,
            ],
            'Server exception, 500' => [
                $this->createMock(ServerExceptionInterface::class),
                500,
            ],
            'RedirectionExceptionInterface, 500' => [
                $this->createMock(RedirectionExceptionInterface::class),
                500,
            ],
            'Client exception, 404' => [
                $this->createMock(ClientExceptionInterface::class),
                404,
            ],
        ];
    }

    /**
     * @dataProvider getMockedException
     */
    public function testShouldExceptionResponseWhenProvidedResponseThrowException(
        Throwable $mockedException,
        int $statusCode,
    ): void {
        $this->responseMock->method('getContent')->willThrowException($mockedException);
        $this->responseMock->method('getStatusCode')->willReturn($statusCode);

        $mappedResponse = $this->mapper->toProxyResponse($this->responseMock);

        self::assertEquals(
            $statusCode,
            $mappedResponse->getResponseCode()->asInt()
        );
    }

    public function testShouldReturnCorrectResponse(): void
    {
        $expectedStatusCode = 200;
        $expectedContent = '';

        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->responseMock->method('getContent')->willReturn($expectedContent);

        $mappedResponse = $this->mapper->toProxyResponse($this->responseMock);

        self::assertEquals($expectedStatusCode, $mappedResponse->getResponseCode()->asInt());
        self::assertEquals($expectedContent, $mappedResponse->getResponseContent()->toString());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new ProxyResponseMapper();
        $this->responseMock = $this->createMock(ResponseInterface::class);
    }
}
