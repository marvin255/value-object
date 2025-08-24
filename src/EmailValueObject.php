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
final class EmailValueObject implements ValueObject
{
    private readonly string $email;

    public function __construct(string $email)
    {
        if (!filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address: {$email}");
        }

        $this->email = $email;
    }

    /**
     * Get the domain part of the email address.
     */
    public function getDomain(): string
    {
        $parts = explode('@', $this->email);

        return $parts[1] ?? '';
    }

    /**
     * Get the local part of the email address.
     */
    public function getLocalPart(): string
    {
        $parts = explode('@', $this->email);

        return $parts[0] ?? '';
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function __toString(): string
    {
        return $this->email;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return strtolower($this->email) === strtolower($other->email);
    }
}
