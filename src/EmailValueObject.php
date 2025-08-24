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
    /**
     * @psalm-var non-empty-string
     */
    private readonly string $email;

    public function __construct(string $email)
    {
        if (empty($email)) {
            throw new \InvalidArgumentException('Email address cannot be empty');
        }

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
     *
     * @psalm-return non-empty-string
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

    /**
     * {@inheritDoc}
     *
     * @psalm-return non-empty-string
     */
    #[\Override]
    public function getValue(): string
    {
        return $this->email;
    }
}
