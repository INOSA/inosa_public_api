<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Test;

use App\DataFixtures\CreatePublicApiClientDataFixtures;
use App\Shared\Application\Json\JsonDecoderInterface;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Infrastructure\Client\Entity\Client;
use App\Shared\Infrastructure\Client\Repository\ClientEntityNotFoundException;
use App\Shared\Infrastructure\Client\Repository\ClientRepository;
use League\OAuth2\Server\AuthorizationServer;
use LogicException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenIssuer
{
    public function __construct(
        private AuthorizationServer $server,
        private ClientRepository $clientRepository,
        private IdentifierFactoryInterface $identifierFactory,
        private JsonDecoderInterface $jsonDecoder,
    ) {
    }

    public function issueTestAccessToken(): string
    {
        $token = $this->server->respondToAccessTokenRequest(
            $this->getTokenRequest($this->getTestClient()),
            new Response()
        );

        return $this->decodeTokenResponse($token);
    }

    private function getTestClient(): Client
    {
        try {
            return $this->clientRepository->getByInosaSiteIdentifier(
                InosaSiteIdentifier::fromIdentifier(
                    $this->identifierFactory->fromString(
                        CreatePublicApiClientDataFixtures::CLIENT_INOSA_SITE_IDENTIFIER
                    )
                )
            );
        } catch (ClientEntityNotFoundException $e) {
            throw new LogicException($e->getMessage());
        }
    }

    private function getTokenRequest(Client $client): ServerRequestInterface
    {
        return (new Psr17Factory())->createServerRequest(
            'POST',
            new Uri('http://public-api.inosa.local/token'),
            [
                'content-type' => [
                    'application/x-www-form-urlencoded',
                ],
            ]
        )->withParsedBody(
            [
                'client_id' => $client->getClientIdentifier()->toString(),
                'client_secret' => $client->getClientSecret()->toString(),
                'grant_type' => 'client_credentials',
            ]
        );
    }

    private function decodeTokenResponse(ResponseInterface $response): string
    {
        /** @phpstan-ignore-next-line */
        return $this->jsonDecoder->decode($response->getBody()->__toString())->get('access_token');
    }
}
