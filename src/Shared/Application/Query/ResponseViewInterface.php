<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

interface ResponseViewInterface
{
    public function getResponse(): string;
    public function getStatusCode(): int;
}
