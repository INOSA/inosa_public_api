<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Client\Entity;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientInternalIdentifier;
use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientSecret;
use App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Client\Repository\ClientRepository;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use LogicException;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;

#[Entity(repositoryClass: ClientRepository::class)]
#[Table(name: 'client_additional_info')]
#[UniqueConstraint(name: "inosaSiteId", columns: ["inosa_site_id"])]
final class ClientEntity
{
    #[Column(type: 'string', length: 36)]
    #[Id]
    private string $id;

    #[Column(type: 'string', length: 36)]
    private string $inosaSiteId;

    #[OneToOne(targetEntity: Client::class)]
    #[JoinColumn(referencedColumnName: "identifier", onDelete: "CASCADE")]
    private Client $client;

    public function __construct(ClientInternalIdentifier $id, InosaSiteIdentifier $inosaSiteId, Client $client)
    {
        $this->id = $id->asString();
        $this->inosaSiteId = $inosaSiteId->asString();
        $this->client = $client;
    }

    public function getId(): ClientInternalIdentifier
    {
        return new ClientInternalIdentifier($this->id);
    }

    public function setId(ClientInternalIdentifier $id): void
    {
        $this->id = $id->asString();
    }

    public function getInosaSiteId(): InosaSiteIdentifier
    {
        return new InosaSiteIdentifier($this->inosaSiteId);
    }

    public function setInosaSiteId(InosaSiteIdentifier $inosaSiteId): void
    {
        $this->inosaSiteId = $inosaSiteId->asString();
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getClientSecret(): ClientSecret
    {
        $secret = $this->client->getSecret();

        if (null === $secret) {
            throw new LogicException('Client must have secret key');
        }

        return new ClientSecret($secret);
    }
}
