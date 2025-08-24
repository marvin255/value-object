<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents a file info.
 *
 * @psalm-api
 */
final class FileInfoValueObject extends \SplFileInfo implements ValueObject
{
    private const ERROR_MESSAGE = 'This value object is read-only and does not support modification methods';

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
}
