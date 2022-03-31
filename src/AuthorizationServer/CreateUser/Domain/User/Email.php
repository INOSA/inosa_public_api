<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Domain\User;

use App\Shared\Domain\StringableInterface;

final class Email implements StringableInterface
{
    public function __construct(private string $email)
    {
    }

    public function toString(): string
    {
        return $this->email;
    }
}
