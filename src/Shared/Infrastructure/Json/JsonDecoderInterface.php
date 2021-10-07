<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Json;

use Inosa\Arrays\ArrayHashMap;

interface JsonDecoderInterface
{
    /**
     * @return ArrayHashMap<mixed>
     */
    public function decode(string $json): ArrayHashMap;
}
