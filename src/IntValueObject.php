<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Value object for int values.
 *
 * @psalm-api
 *
 * @psalm-immutable
 *
 * @psalm-inheritors IntPositiveValueObject|IntNonNegativeValueObject
 */
readonly class IntValueObject implements ValueObject
{
    public function __construct(private readonly int $value)
    {
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
     */
    #[\Override]
    public function getValue(): int
    {
        return $this->value;
    }
}
