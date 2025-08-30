<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object for non-empty string values.
 *
 * @psalm-method non-empty-string getValue()
 * @psalm-method non-empty-string __toString()
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
readonly class StringNonEmptyValueObject extends StringValueObject
{
    /**
     * @throws \InvalidArgumentException if the value is an empty string
     */
    public function __construct(string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('Value must be a non-empty string');
        }

        parent::__construct($value);
    }
}
