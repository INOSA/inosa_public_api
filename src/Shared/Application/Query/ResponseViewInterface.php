<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

interface ResponseViewInterface
{
    public function getResponseContent(): string;
    public function getStatusCode(): int;
}
