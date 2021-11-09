<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Http;

use App\AuthorizationServer\CreatePublicApiClient\Domain\InosaApi\CreatePublicApiUserInosaApiException;
use App\AuthorizationServer\CreatePublicApiClient\Domain\InosaApi\CreatePublicApiUserInosaApiInterface;
use App\Shared\Application\Json\JsonDecoderInterface;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Infrastructure\Http\HttpClient;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CreatePublicApiUserInosaApi implements CreatePublicApiUserInosaApiInterface
{
    public function __construct(
        private HttpClient $httpClient,
        private JsonDecoderInterface $jsonDecoder,
        private IdentifierFactoryInterface $identifierFactory,
    ) {
    }

    public function siteExists(InosaSiteIdentifier $inosaSiteIdentifier): bool
    {
        return $this->getSiteIdentifiers()->contains($inosaSiteIdentifier);
    }

    /**
     * @throws CreatePublicApiUserInosaApiException
     */
    public function createInosaPublicApiUser(InosaSiteIdentifier $inosaSiteIdentifier): void
    {
        try {
            $request = $this->httpClient->post(
                'users',
                ArrayHashMap::create(
                    [
                        'siteId' => $inosaSiteIdentifier->asString()
                    ]
                )
            );

            if (Response::HTTP_CREATED !== $request->getStatusCode()) {
                throw new CreatePublicApiUserInosaApiException(
                    sprintf('Could not create pubic user for site (client) %s', $inosaSiteIdentifier->asString())
                );
            }
        } catch (TransportExceptionInterface $e) {
            throw new CreatePublicApiUserInosaApiException($e->getMessage());
        }
    }

    /**
     * @return ArrayList<InosaSiteIdentifier>
     */
    private function getSiteIdentifiers(): ArrayList
    {
        $response = $this->httpClient->get('sites');

        return $this->getExistingSitesFromResponse($response);
    }

    /**
     * @return ArrayList<InosaSiteIdentifier>
     */
    private function getExistingSitesFromResponse(ResponseInterface $response): ArrayList
    {
        try {
            $decoded = $this->jsonDecoder->decode($response->getContent());
        } catch (TransportExceptionInterface | HttpExceptionInterface $e) {
            throw new LogicException($e->getMessage());
        }

        if (!$decoded->has('data')) {
            throw new LogicException('Could not fetch Inosa sites');
        }

        /** @var array<int, string> $decodedArray */
        $decodedArray = $decoded->get('data');

        return ArrayList::create($decodedArray)
            ->transform(
                fn(array $responseData): InosaSiteIdentifier => InosaSiteIdentifier::fromIdentifier(
                    $this->identifierFactory->fromString($responseData['id'])
                )
            );
    }
}
