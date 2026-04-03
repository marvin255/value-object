<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents a negative integer (less than zero).
 *
 * @psalm-method negative-int getValue()
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final readonly class IntNegativeValueObject extends IntValueObject
{
    /**
     * @throws \InvalidArgumentException if the value is not a negative integer
     */
    public function __construct(int $value)
    {
        if ($value >= 0) {
            throw new \InvalidArgumentException('Value must be a negative integer less than zero');
        }

        parent::__construct($value);
    }
}
