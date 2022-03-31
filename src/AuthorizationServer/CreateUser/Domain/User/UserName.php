<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Domain\User;

use App\Shared\Domain\StringableInterface;

final class UserName implements StringableInterface
{
    public function __construct(private string $userName)
    {
    }

    public function toString(): string
    {
        return $this->userName;
    }
}
