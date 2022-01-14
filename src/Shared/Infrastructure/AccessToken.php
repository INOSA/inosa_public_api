<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Infrastructure\Client\Entity\Client;
use DateTimeImmutable;
use Lcobucci\JWT\Token;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use LogicException;

final class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
    use EntityTrait;
    use TokenEntityTrait;

    private const PUBLIC_API_ISS = 'public-api';

    public function __construct(private Client $clientEntity)
    {
    }

    public function __toString(): string
    {
        return $this->configureJWT()->toString();
    }

    private function configureJWT(): Token
    {
        $this->initJwtConfiguration();

        return $this->jwtConfiguration->builder()
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getStringIdentifier())
            ->issuedAt(new DateTimeImmutable())
            ->issuedBy(self::PUBLIC_API_ISS)
            ->canOnlyBeUsedAfter(new DateTimeImmutable())
            ->expiresAt($this->getExpiryDateTime())
            ->relatedTo('')
            ->withClaim('scopes', $this->getScopes())
            ->withClaim(
                'client',
                [
                    'siteId' => $this->clientEntity->getInosaSiteId()->toString(),
                ],
            )
            ->getToken($this->jwtConfiguration->signer(), $this->jwtConfiguration->signingKey());
    }

    private function getStringIdentifier(): string
    {
        $identifier = $this->getIdentifier();

        if (!is_string($identifier)) {
            throw new LogicException('Identifier must be of type int');
        }

        return $identifier;
    }
}
