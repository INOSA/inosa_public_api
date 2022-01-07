<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Domain\User;

use App\Shared\Domain\StringableInterface;

final class FirstName implements StringableInterface
{
    public function __construct(private string $firstName)
    {
    }

    public function toString(): string
    {
        return $this->firstName;
    }
}
