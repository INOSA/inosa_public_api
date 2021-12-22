<?php

declare(strict_types=1);

namespace App\Shared\Domain;

final class ResponseContent
{
    public function __construct(private string $content)
    {
    }

    public function asString(): string
    {
        return $this->content;
    }
}
