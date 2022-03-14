<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Identifier;

use App\Shared\Domain\Identifier\Identifier;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

final class UuidIdentifierFactory implements IdentifierFactoryInterface
{
    public function create(): Identifier
    {
        return new Identifier(Uuid::uuid4()->toString());
    }

    /**
     * @throws InvalidIdentifierException
     */
    public function fromString(string $identifier): Identifier
    {
        if (false === Uuid::isValid($identifier)) {
            throw new InvalidIdentifierException(
                sprintf('%s is not valid uuid v4 string', $identifier)
            );
        }

        try {
            return new Identifier(Uuid::fromString($identifier)->toString());
        } catch (InvalidUuidStringException $exception) {
            throw new InvalidIdentifierException($exception->getMessage());
        }
    }
}
