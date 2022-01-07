<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\Response\ProxyResponseMapper;

use App\Shared\Domain\ResponseCode;
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
     * @return array<int, array<int, MockObject|\Throwable>>
     */
    public function getMockedException(): array
    {
        return [
            [
                $this->createMock(TransportExceptionInterface::class),
            ],
            [
                $this->createMock(ClientExceptionInterface::class),
            ],
            [
                $this->createMock(ServerExceptionInterface::class),
            ],
            [
                $this->createMock(RedirectionExceptionInterface::class),
            ],
        ];
    }

    /**
     * @dataProvider getMockedException
     */
    public function testShouldExceptionResponseWhenProvidedResponseThrowException(
        Throwable $mockedException
    ): void {
        $this->responseMock->method('getContent')->willThrowException($mockedException);

        $mappedResponse = $this->mapper->toProxyResponse($this->responseMock);

        self::assertEquals(
            ResponseCode::INTERNAL_SERVER_ERROR_CODE,
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
        self::assertEquals($expectedContent, $mappedResponse->getResponseContent()->asString());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new ProxyResponseMapper();
        $this->responseMock = $this->createMock(ResponseInterface::class);
    }
}
