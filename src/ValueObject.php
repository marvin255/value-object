<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * @psalm-immutable
 */
interface ValueObject extends \Stringable
{
    /**
     * Check if the current value object is equal to another value object.
     */
    public function equals(ValueObject $other): bool;

    /**
     * Get the underlying value of the value object.
     */
    public function getValue(): mixed;
}
