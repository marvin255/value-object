<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents a positive integer (greater than zero).
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final readonly class PositiveIntValueObject implements ValueObject
{
    /**
     * @psalm-var positive-int
     */
    private readonly int $value;

    /**
     * @throws \InvalidArgumentException if the value is not a positive integer
     */
    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Value must be a positive integer greater than zero');
        }

        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-return non-empty-string
     */
    #[\Override]
    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->getValue() === $other->getValue();
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-return positive-int
     */
    #[\Override]
    public function getValue(): int
    {
        return $this->value;
    }
}
