<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents a percentage (0-100 inclusive).
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final readonly class PercentageValueObject extends FloatValueObject
{
    /**
     * @throws \InvalidArgumentException if the value is not between 0 and 100
     */
    public function __construct(float $value)
    {
        if ($value < 0 || $value > 100) {
            throw new \InvalidArgumentException('Percentage must be between 0 and 100');
        }

        parent::__construct($value);
    }

    /**
     * Returns a complementary percentage (100% - current value).
     */
    public function getComplimentary(): self
    {
        return new self(100.0 - $this->getValue());
    }

    /**
     * Applies this percentage to a number and returns the result.
     */
    public function applyToNumber(float $number): float
    {
        return ($this->getValue() / 100.0) * $number;
    }
}
