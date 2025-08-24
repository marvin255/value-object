<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents an Email address.
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final readonly class EmailValueObject extends StringValueObject
{
    /**
     * @throws \InvalidArgumentException if the email is empty or invalid
     */
    public function __construct(string $email)
    {
        if (empty($email)) {
            throw new \InvalidArgumentException('Email address cannot be empty');
        }

        if (!filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address: {$email}");
        }

        parent::__construct($email);
    }

    /**
     * Get the domain part of the email address.
     */
    public function getDomain(): string
    {
        $parts = explode('@', $this->getValue());

        return $parts[1] ?? '';
    }

    /**
     * Get the local part of the email address.
     */
    public function getLocalPart(): string
    {
        $parts = explode('@', $this->getValue());

        return $parts[0] ?? '';
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
     */
    #[\Override]
    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof StringValueObject) {
            return false;
        }

        return strtolower($this->getValue()) === strtolower($other->getValue());
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
