<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

interface ValueObject
{
    /**
     * Get string representation of the value object.
     */
    public function __toString(): string;

    /**
     * Check if the current value object is equal to another value object.
     */
    public function equals(ValueObject $other): bool;
}
