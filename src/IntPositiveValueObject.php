<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents a positive integer (greater than zero).
 *
 * @psalm-method positive-int getValue()
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final readonly class IntPositiveValueObject extends IntValueObject
{
    /**
     * @throws \InvalidArgumentException if the value is not a positive integer
     */
    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Value must be a positive integer greater than zero');
        }

        parent::__construct($value);
    }
}
