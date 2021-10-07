<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Http;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientInternalIdentifier;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Domain\InosaApi\InosaApiException;
use App\Shared\Domain\InosaApi\InosaApiInterface;
use App\Shared\Infrastructure\Json\JsonDecoderInterface;
use Inosa\Arrays\ArrayList;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class InosaApiClient implements InosaApiInterface
{
    public function __construct(
        private string $apiUrl,
        private HttpClientInterface $apiClient,
        private JsonDecoderInterface $jsonDecoder,
        private IdentifierFactoryInterface $identifierFactory,
    ) {
    }

    public function siteExists(InosaSiteIdentifier $inosaSiteIdentifier): bool
    {
        return $this->getSiteIdentifiers()->contains($inosaSiteIdentifier);
    }

    /**
     * @throws InosaApiException
     */
    public function createInosaPublicApiUser(ClientInternalIdentifier $clientIdentifier): void
    {
        try {
            $request = $this->apiClient->request(
                'POST',
                sprintf('%s/users', $this->apiUrl),
                [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'body' => [
                        'siteId' => $clientIdentifier->asString()
                    ]
                ]
            );

            if (Response::HTTP_CREATED !== $request->getStatusCode()) {
                throw new InosaApiException(
                    sprintf('Could not create pubic user for site (client) %s', $clientIdentifier->asString())
                );
            }
        } catch (TransportExceptionInterface $e) {
            throw new InosaApiException($e->getMessage());
        }
    }

    /**
     * @return ArrayList<InosaSiteIdentifier>
     */
    private function getSiteIdentifiers(): ArrayList
    {
        try {
            $request = $this->apiClient->request(
                'GET',
                sprintf('%s/sites', $this->apiUrl)
            );

            $decoded = $this->jsonDecoder->decode($request->getContent());

            if (!$decoded->has('data')) {
                throw new LogicException('Could not fetch Inosa sites');
            }

            return ArrayList::create($decoded->get('data'))
                ->transform(
                    fn(array $responseData): InosaSiteIdentifier => InosaSiteIdentifier::fromIdentifier(
                        $this->identifierFactory->fromString($responseData['id'])
                    )
                );
        } catch (TransportExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $e) {
            throw new LogicException($e->getMessage());
        }
    }
}
