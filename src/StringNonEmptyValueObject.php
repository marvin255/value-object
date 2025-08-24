<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Value object for string values.
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

    /**
     * {@inheritDoc}
     *
     * @psalm-return non-empty-string
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    #[\Override]
    public function __toString(): string
    {
        return parent::__toString();
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-return non-empty-string
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    #[\Override]
    public function getValue(): string
    {
        return parent::getValue();
    }
}
