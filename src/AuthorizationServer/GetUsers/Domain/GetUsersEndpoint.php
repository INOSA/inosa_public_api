<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetUsers\Domain;

use App\AuthorizationServer\CreateUser\Domain\User\Email;
use App\AuthorizationServer\CreateUser\Domain\User\FirstName;
use App\AuthorizationServer\CreateUser\Domain\User\LastName;
use App\AuthorizationServer\CreateUser\Domain\User\UserName;
use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use App\Shared\Domain\Stringify;
use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayList;

final class GetUsersEndpoint implements EndpointInterface
{
    private const URL = 'users';

    /**
     * @param ArrayList<DepartmentIdentifier> $departmentIds
     */
    public function __construct(
        public readonly ArrayList $departmentIds,
        public readonly ?FirstName $firstName = null,
        public readonly ?LastName $lastName = null,
        public readonly ?UserName $userName = null,
        public readonly ?Email $email = null,
    ) {
    }

    public function getUrl(): Url
    {
        $queryParams = Stringify::fromString('?');

        if (false === $this->getDepartmentIdsQueryParam()->isEmpty()) {
            $queryParams = $queryParams
                ->concat($this->getDepartmentIdsQueryParam())
                ->concat(Stringify::fromString('&'));
        }

        if (false === $this->getFirstNameQueryParam()->isEmpty()) {
            $queryParams = $queryParams
                ->concat($this->getFirstNameQueryParam())
                ->concat(Stringify::fromString('&'));
        }

        if (false === $this->getLastNameQueryParam()->isEmpty()) {
            $queryParams = $queryParams
                ->concat($this->getLastNameQueryParam())
                ->concat(Stringify::fromString('&'));
        }

        if (false === $this->getUserNameQueryParam()->isEmpty()) {
            $queryParams = $queryParams
                ->concat($this->getUserNameQueryParam())
                ->concat(Stringify::fromString('&'));
        }

        if (false === $this->getEmailQueryParam()->isEmpty()) {
            $queryParams = $queryParams
                ->concat($this->getEmailQueryParam())
                ->concat(Stringify::fromString('&'));
        }

        if ($queryParams->equals(Stringify::fromString('?'))) {
            return new Url(Stringify::fromString(self::URL)->toString());
        }

        $urlString = Stringify::fromString(self::URL)
            ->concat($queryParams)
            ->removeLastCharacter()
            ->toString();

        return new Url($urlString);
    }

    private function getDepartmentIdsQueryParam(): Stringify
    {
        if (true === $this->departmentIds->isEmpty()) {
            return Stringify::empty();
        }

        /** @var ArrayList<Stringify> $stringableDepartmentIds */
        $stringableDepartmentIds = $this->departmentIds
            ->transform(
                fn(DepartmentIdentifier $departmentIdentifier): Stringify => Stringify::fromString('departmentId[]=')
                    ->concat(Stringify::fromStringable($departmentIdentifier))
                    ->concat(Stringify::fromString('&'))
            );

        return Stringify::empty()
            ->concatMultiple(...$stringableDepartmentIds->toArray())
            ->removeLastCharacter();
    }

    private function getFirstNameQueryParam(): Stringify
    {
        if (null === $this->firstName) {
            return Stringify::empty();
        }

        return Stringify::fromString('firstname=')->concat(Stringify::fromStringable($this->firstName));
    }

    private function getLastNameQueryParam(): Stringify
    {
        if (null === $this->lastName) {
            return Stringify::empty();
        }

        return Stringify::fromString('lastname=')->concat(Stringify::fromStringable($this->lastName));
    }

    private function getUserNameQueryParam(): Stringify
    {
        if (null === $this->userName) {
            return Stringify::empty();
        }

        return Stringify::fromString('username=')->concat(Stringify::fromStringable($this->userName));
    }

    private function getEmailQueryParam(): Stringify
    {
        if (null === $this->email) {
            return Stringify::empty();
        }

        return Stringify::fromString('email=')->concat(Stringify::fromStringable($this->email));
    }
}
