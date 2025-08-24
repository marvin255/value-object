<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use Marvin255\ValueObject\FileInfoValueObject;
use Marvin255\ValueObject\ValueObject;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class FileInfoValueObjectTest extends BaseCase
{
    public function testConstructWithEmptyPath(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be a non-empty string');

        new FileInfoValueObject('');
    }

    public function testConstructTrimsPath(): void
    {
        $path = '/some/path/to/file.txt';
        $object = new FileInfoValueObject(" \t\n{$path} \t\n");

        $this->assertSame($path, $object->getValue());
    }

    public function testGetFileInfo(): void
    {
        $path = '/some/path/to/file.txt';
        $object = new FileInfoValueObject($path);

        $fileInfo = $object->getFileInfo();
        $this->assertInstanceOf(\SplFileInfo::class, $fileInfo);
        $this->assertSame($path, $fileInfo->getPathname());
    }

    public function testToString(): void
    {
        $path = '/some/path/to/file.txt';
        $object = new FileInfoValueObject($path);

        $this->assertSame($path, (string) $object);
    }

    #[DataProvider('equalsDataProvider')]
    public function testEquals(ValueObject $object1, ValueObject $object2, bool $expected): void
    {
        $this->assertSame($expected, $object1->equals($object2));
    }

    public static function equalsDataProvider(): array
    {
        return [
            'real same path' => [
                'object1' => new FileInfoValueObject(__FILE__),
                'object2' => new FileInfoValueObject(__FILE__),
                'expected' => true,
            ],
            'real different paths' => [
                'object1' => new FileInfoValueObject(__FILE__),
                'object2' => new FileInfoValueObject(__DIR__),
                'expected' => false,
            ],
            'real path and not real path' => [
                'object1' => new FileInfoValueObject(__FILE__),
                'object2' => new FileInfoValueObject('not-existing-file.txt'),
                'expected' => false,
            ],
            'not real paths, same path' => [
                'object1' => new FileInfoValueObject('not-existing-file.txt'),
                'object2' => new FileInfoValueObject('not-existing-file.txt'),
                'expected' => true,
            ],
            'not real paths, different paths' => [
                'object1' => new FileInfoValueObject('not-existing-file-1.txt'),
                'object2' => new FileInfoValueObject('not-existing-file-2.txt'),
                'expected' => false,
            ],
            'different types' => [
                'object1' => new FileInfoValueObject(__FILE__),
                'object2' => new class() implements ValueObject {
                    #[\Override]
                    public function __toString(): string
                    {
                        return __FILE__;
                    }

                    #[\Override]
                    public function equals(ValueObject $other): bool
                    {
                        return true;
                    }

                    #[\Override]
                    public function getValue(): string
                    {
                        return __FILE__;
                    }
                },
                'expected' => false,
            ],
        ];
    }

    public function testGetValue(): void
    {
        $path = '/some/path/to/file.txt';
        $object = new FileInfoValueObject($path);

        $this->assertSame($path, $object->getValue());
    }
}
