<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\CreateUser;

use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use Inosa\Arrays\ArrayHashMap;

final class CreateUserParamBuilder
{
    /**
     * @var string[]
     */
    private array $permissionGroups = [];

    /**
     * @var string[]
     */
    private array $roles = [];
    private string $departmentId = "";
    private string $email = "";
    private string $firstName = "";
    private string $lastName = "";
    private string $userName = "";
    private string $id = "";

    public function __construct(
        private JsonEncoderInterface $jsonEncoder,
        private IdentifierFactoryInterface $idFactory
    ) {
    }

    public function validRequest(): self
    {
        $this->permissionGroups = [];
        $this->roles = [];
        $this->departmentId = 'EA4A657A-C618-11E7-ABC4-CEC278B6B50A';
        $this->email = 'testemail@inosa.no';
        $this->userName = 'testuser123';
        $this->firstName = 'test';
        $this->lastName = 'user';
        $this->id = $this->idFactory->create()->toString();

        return $this;
    }

    public function withEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function withUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function withFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function withRandomRoles(int $numberOfRandomRoles = 5): self
    {
        for ($i = 0; $i < $numberOfRandomRoles; $i++) {
            $this->roles[] = $this->idFactory->create()->toString();
        }

        return $this;
    }

    public function withRandomPermissionGroups(int $numberOfPermissionGroups = 5): self
    {
        for ($i = 0; $i < $numberOfPermissionGroups; $i++) {
            $this->permissionGroups[] = $this->idFactory->create()->toString();
        }

        return $this;
    }

    public function withLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function emptyId(): self
    {
        $this->id = "";

        return $this;
    }

    public function withId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function buildWithoutId(): string
    {
        return $this->jsonEncoder->encode($this->internalBuild()->remove('id'));
    }

    public function buildWithoutUserName(): string
    {
        return $this->jsonEncoder->encode($this->internalBuild()->remove('userName'));
    }

    public function buildWithoutFirstName(): string
    {
        return $this->jsonEncoder->encode($this->internalBuild()->remove('firstName'));
    }

    public function buildWithoutLastName(): string
    {
        return $this->jsonEncoder->encode($this->internalBuild()->remove('lastName'));
    }

    public function buildWithoutPermissionGroups(): string
    {
        return $this->jsonEncoder->encode($this->internalBuild()->remove('permissionsGroups'));
    }

    public function buildWithoutRoles(): string
    {
        return $this->jsonEncoder->encode($this->internalBuild()->remove('roles'));
    }

    public function buildWithoutDepartmentId(): string
    {
        return $this->jsonEncoder->encode($this->internalBuild()->remove('departmentId'));
    }

    public function buildWithoutEmail(): string
    {
        return $this->jsonEncoder->encode($this->internalBuild()->remove('email'));
    }

    public function build(): string
    {
        return $this->jsonEncoder->encode($this->internalBuild());
    }

    /**
     * @return ArrayHashMap<mixed>
     */
    private function internalBuild(): ArrayHashMap
    {
        return ArrayHashMap::create(
            [
                "permissionsGroups" => $this->permissionGroups,
                "roles" => $this->roles,
                "departmentId" => $this->departmentId,
                "email" => $this->email,
                "firstName" => $this->firstName,
                "lastName" => $this->lastName,
                "userName" => $this->userName,
                "id" => $this->id,
            ]
        );
    }
}
