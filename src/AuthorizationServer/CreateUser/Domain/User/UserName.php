<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Domain\User;

final class UserName
{
    public function __construct(private string $userName)
    {
    }

    public function toString(): string
    {
        return $this->userName;
    }
}
