<?php

declare(strict_types=1);

namespace App\Shared\Domain;

final class ResponseContent
{
    public function __construct(private string $content)
    {
    }

    public function toString(): string
    {
        return $this->content;
    }

    public static function internalServerError(): self
    {
        return new self('Internal server error, please contact with the Administrator.');
    }

    public static function notFound(): self
    {
        return new self('Resource you\'re looking for does not exist.');
    }
}
