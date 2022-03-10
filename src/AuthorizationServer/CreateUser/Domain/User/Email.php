<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Domain\User;

final class Email
{
    public function __construct(private string $email)
    {
    }

    public function toString(): string
    {
        return $this->email;
    }
}
