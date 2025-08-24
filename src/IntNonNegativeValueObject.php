<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents a non-negative integer (zero or greater).
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final readonly class IntNonNegativeValueObject implements ValueObject
{
    /**
     * @psalm-var non-negative-int
     */
    private readonly int $value;

    /**
     * @throws \InvalidArgumentException if the value is not a non-negative integer
     */
    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Value must be an integer greater then or equal zero');
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
     * @psalm-return non-negative-int
     */
    #[\Override]
    public function getValue(): int
    {
        return $this->value;
    }
}
