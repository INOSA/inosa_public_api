<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Json;

use Inosa\Arrays\ArrayHashMap;
use JsonException;
use LogicException;

final class JsonDecoder implements JsonDecoderInterface
{
    /**
     * @return ArrayHashMap<mixed>
     * @throws LogicException
     */
    public function decode(string $json): ArrayHashMap
    {
        try {
            return ArrayHashMap::create(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            throw new LogicException($e->getMessage());
        }
    }
}
