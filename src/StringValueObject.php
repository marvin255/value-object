<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object for string values.
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
readonly class StringValueObject implements ValueObject
{
    public function __construct(private readonly string $value)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function __toString(): string
    {
        return $this->value;
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
    public function getValue(): string
    {
        return $this->value;
    }
}
