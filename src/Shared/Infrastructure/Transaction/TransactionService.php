<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Transaction;

use Closure;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Throwable;

final class TransactionService implements TransactionServiceInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws RuntimeException
     */
    public function execute(Closure $callable): void
    {
        try {
            $this->entityManager->wrapInTransaction($callable);
        } catch (Throwable $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
