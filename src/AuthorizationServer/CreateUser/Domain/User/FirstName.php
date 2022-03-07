<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Domain\User;

final class FirstName
{
    public function __construct(private string $firstName)
    {
    }

    public function toString(): string
    {
        return $this->firstName;
    }
}
