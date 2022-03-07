<?php

declare(strict_types=1);

namespace App\Shared\Domain;

final class ResponseCode
{
    public const INTERNAL_SERVER_ERROR_CODE = 500;
    public const NOT_FOUND_CODE = 404;

    public function __construct(private int $code)
    {
    }

    public function isServerError(): bool
    {
        return self::INTERNAL_SERVER_ERROR_CODE <= $this->code;
    }

    public function isSuccess(): bool
    {
        return 200 <= $this->code && 300 > $this->code;
    }

    public static function internalServerError(): self
    {
        return new self(self::INTERNAL_SERVER_ERROR_CODE);
    }

    public static function notFound(): self
    {
        return new self(self::NOT_FOUND_CODE);
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
