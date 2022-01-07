<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Json;

use App\Shared\Application\Json\JsonDecoderInterface;
use Inosa\Arrays\ArrayHashMap;
use JsonException;
use LogicException;

final class JsonDecoder implements JsonDecoderInterface
{
    private const EMPTY_STRING = "";

    /**
     * @return ArrayHashMap<mixed>
     * @throws LogicException
     */
    public function decode(string $json): ArrayHashMap
    {
        if ($json === self::EMPTY_STRING) {
            return ArrayHashMap::create([]);
        }

        try {
            /** @var array<mixed> $decodedArray */
            $decodedArray = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            return ArrayHashMap::create($decodedArray);
        } catch (JsonException $e) {
            throw new LogicException($e->getMessage());
        }
    }
}
