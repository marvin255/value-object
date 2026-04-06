<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

use BcMath\Number;

/**
 * Immutable value object for arbitrary-precision decimal numbers using BcMath.
 *
 * Unlike FloatValueObject, this uses BC Math's Number class which stores
 * numeric values as strings to provide arbitrary precision without floating
 * point precision loss.
 *
 * Accepts both Number and string in constructor, converting strings to Number internally.
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
readonly class BcMathNumberValueObject implements ValueObject
{
    private readonly Number $value;

    public function __construct(Number|string $value)
    {
        $this->value = $value instanceof Number ? $value : new Number($value);
    }

    /**
     * {@inheritDoc}
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

        return (string) $this->getValue() === (string) $other->getValue();
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getValue(): Number
    {
        return $this->value;
    }
}
