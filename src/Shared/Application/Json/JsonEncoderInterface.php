<?php

declare(strict_types=1);

namespace App\Shared\Application\Json;

use Inosa\Arrays\ArrayHashMap;
use LogicException;

interface JsonEncoderInterface
{
    /**
     * @param ArrayHashMap<mixed> $toEncode
     * @throws LogicException
     */
    public function encode(ArrayHashMap $toEncode): string;
}
