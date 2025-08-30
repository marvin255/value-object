<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

/**
 * Immutable value object that represents a file info.
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final readonly class FileInfoValueObject extends StringNonEmptyValueObject
{
    private readonly \SplFileInfo $fileInfo;

    /**
     * @throws \InvalidArgumentException if the path is empty
     */
    public function __construct(string $pathname)
    {
        $trimmedPath = trim($pathname);
        $this->fileInfo = new \SplFileInfo($trimmedPath);

        parent::__construct($trimmedPath);
    }

    /**
     * Get the file info.
     */
    public function getFileInfo(): \SplFileInfo
    {
        return $this->fileInfo;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress ImpureMethodCall
     */
    #[\Override]
    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof StringValueObject) {
            return false;
        } elseif (!$other instanceof self) {
            return $this->getFileInfo()->getRealPath() === $other->getValue();
        }

        $realPath = $this->getFileInfo()->getRealPath();
        $otherRealPath = $other->getFileInfo()->getRealPath();

        if ($realPath === false && $otherRealPath === false) {
            return $this->getFileInfo()->getPathname() === $other->getFileInfo()->getPathname();
        }

        return $realPath === $otherRealPath;
    }
}
