<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents a non-positive integer (zero or less).
 *
 * @psalm-method non-positive-int getValue()
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final readonly class IntNonPositiveValueObject extends IntValueObject
{
    /**
     * @throws \InvalidArgumentException if the value is not a non-positive integer
     */
    public function __construct(int $value)
    {
        if ($value > 0) {
            throw new \InvalidArgumentException('Value must be an integer less than or equal to zero');
        }

        parent::__construct($value);
    }
}
