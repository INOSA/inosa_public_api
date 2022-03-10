<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Domain\User;

final class LastName
{
    public function __construct(private string $lastName)
    {
    }

    public function toString(): string
    {
        return $this->lastName;
    }
}
