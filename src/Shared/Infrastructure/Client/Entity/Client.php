<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Client\Entity;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientIdentifier;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientInternalIdentifier;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientName;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientSecret;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\Grant;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Domain\Scope\Scope;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Inosa\Arrays\ArrayList;
use League\Bundle\OAuth2ServerBundle\Model\AbstractClient;
use League\Bundle\OAuth2ServerBundle\Model\Grant as LeagueGrant;
use League\Bundle\OAuth2ServerBundle\Model\Scope as LeagueScope;
use LogicException;

#[Entity]
#[UniqueConstraint(name: "siteId", columns: ["site_Id"])]
class Client extends AbstractClient
{
    #[Id]
    #[Column(type: 'string', length: 32)]
    protected $identifier;

    #[Column(type: 'string', length: 36)]
    private string $id;

    #[Column(type: 'string', length: 36)]
    private string $siteId;

    /**
     * @param ArrayList<Grant> $grants
     * @param ArrayList<Scope> $scopes
     */
    public function __construct(
        ClientInternalIdentifier $id,
        InosaSiteIdentifier $siteId,
        ClientName $name,
        ClientIdentifier $identifier,
        ClientSecret $secret,
        ArrayList $grants,
        ArrayList $scopes,
    ) {
        parent::__construct($name->toString(), $identifier->toString(), $secret->toString());

        $this->id = $id->toString();
        $this->siteId = $siteId->toString();
        $this->setClientGrants($grants);
        $this->setClientScopes($scopes);
    }

    public function getId(): ClientInternalIdentifier
    {
        return new ClientInternalIdentifier($this->id);
    }

    public function setId(ClientInternalIdentifier $id): void
    {
        $this->id = $id->toString();
    }

    public function getInosaSiteId(): InosaSiteIdentifier
    {
        return new InosaSiteIdentifier($this->siteId);
    }

    public function setInosaSiteId(InosaSiteIdentifier $inosaSiteId): void
    {
        $this->siteId = $inosaSiteId->toString();
    }

    public function getClientSecret(): ClientSecret
    {
        $secret = $this->getSecret();

        if (null === $secret) {
            throw new LogicException('Client secret must be set');
        }

        return new ClientSecret($secret);
    }

    public function getClientName(): ClientName
    {
        return new ClientName($this->getName());
    }

    public function getClientIdentifier(): ClientIdentifier
    {
        return new ClientIdentifier($this->getIdentifier());
    }

    /**
     * @return ArrayList<Scope>
     */
    public function getClientScopes(): ArrayList
    {
        return ArrayList::create($this->getScopes())->transform(
            static fn(string $scope): Scope => Scope::fromString($scope)
        );
    }

    /**
     * @param ArrayList<Scope> $clientScopes
     */
    public function setClientScopes(ArrayList $clientScopes): void
    {
        /** @var LeagueScope[] $scopes */
        $scopes = $clientScopes->transform(
            static fn(Scope $scope): LeagueScope => new LeagueScope($scope->toString())
        )->toArray();

        $this->setScopes(...$scopes);
    }

    /**
     * @param ArrayList<Grant> $clientGrants
     */
    public function setClientGrants(ArrayList $clientGrants): void
    {
        /** @var LeagueGrant[] $grants */
        $grants = $clientGrants->transform(
            static fn(Grant $grant): LeagueGrant => new LeagueGrant($grant->toString())
        )->toArray();

        $this->setGrants(...$grants);
    }

    /**
     * @return ArrayList<Grant>
     */
    public function getClientGrants(): ArrayList
    {
        return ArrayList::create($this->getGrants())->transform(
            static fn(string $grant): Grant => Grant::fromString($grant)
        );
    }
}
