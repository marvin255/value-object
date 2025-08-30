<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents a non-negative integer (zero or greater).
 *
 * @psalm-method non-negative-int getValue()
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final readonly class IntNonNegativeValueObject extends IntValueObject
{
    /**
     * @throws \InvalidArgumentException if the value is not a non-negative integer
     */
    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Value must be an integer greater then or equal zero');
        }

        parent::__construct($value);
    }
}
