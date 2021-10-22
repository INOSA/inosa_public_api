<?php

declare(strict_types=1);

namespace App\Shared\Application\Json;

use LogicException;

interface JsonEncoderInterface
{
    /**
     * @throws LogicException
     */
    public function encode(string $toEncode): string;
}
