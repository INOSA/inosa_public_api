<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Shared\Infrastructure\Json;

use App\Shared\Application\Json\JsonEncoderInterface;
use JsonException;
use LogicException;

final class JsonEncoder implements JsonEncoderInterface
{
    /**
     * @inheritDoc
     */
    public function encode(string $toEncode): string
    {
        try {
            return json_encode($toEncode, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new LogicException($e->getMessage());
        }
    }
}
