<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Value object for float values.
 *
 * @psalm-api
 *
 * @psalm-immutable
 *
 * @psalm-inheritors PercentageValueObject
 */
readonly class FloatValueObject implements ValueObject
{
    private const float DELTA = 1e-9;

    public function __construct(private readonly float $value)
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
        if (!$other instanceof self) {
            return false;
        }

        return abs($this->getValue() - $other->getValue()) <= self::DELTA;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getValue(): float
    {
        return $this->value;
    }
}
