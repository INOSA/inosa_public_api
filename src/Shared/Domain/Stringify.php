<?php

declare(strict_types=1);

namespace App\Shared\Domain;

final class Stringify implements StringableInterface
{
    private function __construct(public readonly string $value)
    {
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function fromStringable(StringableInterface $stringable): self
    {
        return new self($stringable->toString());
    }

    public static function createEmpty(): self
    {
        return new self('');
    }

    public function concat(Stringify $value): self
    {
        return new self($this->value . $value->toString());
    }

    public function concatMultiple(Stringify ...$stringifies): self
    {
        $concatenated = self::createEmpty();

        foreach ($stringifies as $stringify) {
            $concatenated = $concatenated->concat($stringify);
        }

        return $concatenated;
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    public function equals(Stringify $stringify): bool
    {
        return $this->value === $stringify->toString();
    }

    public function removeLastCharacter(): self
    {
        return new self(substr_replace($this->value, '', -1));
    }

    public function toString(): string
    {
        return $this->value;
    }
}
