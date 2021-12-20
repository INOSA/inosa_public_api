<?php

declare(strict_types=1);

namespace App\Shared\Domain\Url;

final class Url
{
    public function __construct(private string $url)
    {
    }

    public function asString(): string
    {
        return $this->url;
    }
}
