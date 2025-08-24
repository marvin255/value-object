<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents a file info.
 *
 * @psalm-api
 *
 * @psalm-immutable
 *
 * @psalm-suppress MutableDependency
 * @psalm-suppress ImpureMethodCall
 */
final class FileInfoValueObject extends \SplFileInfo implements ValueObject
{
    private const ERROR_MESSAGE = 'This value object is read-only and does not support modification methods';

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $value;

    /**
     * @throws \InvalidArgumentException if the path is empty
     */
    public function __construct(string $pathname)
    {
        $trimmedPath = trim($pathname);
        if ($trimmedPath === '') {
            throw new \InvalidArgumentException('Path can\'t be empty');
        }

        $this->value = $trimmedPath;
        parent::__construct($trimmedPath);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-param resource|null $context
     */
    #[\Override]
    public function openFile(string $mode = 'r', bool $useIncludePath = false, $context = null): \SplFileObject
    {
        throw new \BadMethodCallException(self::ERROR_MESSAGE);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MethodSignatureMismatch
     */
    #[\Override]
    public function setFileClass(string $class = \SplFileObject::class): void
    {
        throw new \BadMethodCallException(self::ERROR_MESSAGE);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MethodSignatureMismatch
     */
    #[\Override]
    public function setInfoClass(string $class = \SplFileInfo::class): void
    {
        throw new \BadMethodCallException(self::ERROR_MESSAGE);
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-return non-empty-string
     */
    #[\Override]
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        $realPath = $this->getRealPath();
        $otherRealPath = $other->getRealPath();

        if ($realPath === false && $otherRealPath === false) {
            return $this->getPathname() === $other->getPathname();
        }

        return $realPath === $otherRealPath;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-return non-empty-string
     */
    #[\Override]
    public function getValue(): string
    {
        return $this->value;
    }
}
