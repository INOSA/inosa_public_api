<?php

declare(strict_types=1);

namespace App\Shared\Domain;

final class ResponseCode
{
    public const INTERNAL_SERVER_ERROR_CODE = 500;

    public function __construct(private int $code)
    {
    }

    public static function internalServerError(): self
    {
        return new self(self::INTERNAL_SERVER_ERROR_CODE);
    }

    public function equals(ResponseCode $responseCode): bool
    {
        return $this->code === $responseCode->asInt();
    }

    public function asInt(): int
    {
        return $this->code;
    }
}
