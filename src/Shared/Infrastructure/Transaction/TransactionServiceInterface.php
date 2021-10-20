<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Transaction;

use Closure;
use RuntimeException;

interface TransactionServiceInterface
{
    /**
     * @throws RuntimeException
     */
    public function execute(Closure $callable): void;
}
