<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use Marvin255\ValueObject\StringNonEmptyValueObject;
use Marvin255\ValueObject\ValueObject;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class StringNonEmptyValueObjectTest extends BaseCase
{
    public function testEmptyStringInConstructor(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new StringNonEmptyValueObject('');
    }

    public function testToString(): void
    {
        $string = 'Hello, World!';
        $valueObject = new StringNonEmptyValueObject($string);

        $this->assertSame($string, (string) $valueObject);
    }

    #[DataProvider('provideEquals')]
    public function testEquals(ValueObject $object1, ValueObject $object2, bool $expected): void
    {
        $this->assertSame($expected, $object1->equals($object2));
    }

    public static function provideEquals(): array
    {
        return [
            'same values' => [
                'object1' => new StringNonEmptyValueObject('test'),
                'object2' => new StringNonEmptyValueObject('test'),
                'expected' => true,
            ],
            'different values' => [
                'object1' => new StringNonEmptyValueObject('test1'),
                'object2' => new StringNonEmptyValueObject('test2'),
                'expected' => false,
            ],
            'different types' => [
                'object1' => new StringNonEmptyValueObject('test'),
                'object2' => new class() implements ValueObject {
                    #[\Override]
                    public function __toString(): string
                    {
                        return 'test';
                    }

                    #[\Override]
                    public function equals(ValueObject $other): bool
                    {
                        return true;
                    }

                    #[\Override]
                    public function getValue(): string
                    {
                        return 'test';
                    }
                },
                'expected' => false,
            ],
        ];
    }

    public function testGetValue(): void
    {
        $string = 'Hello, World!';
        $valueObject = new StringNonEmptyValueObject($string);

        $this->assertSame($string, $valueObject->getValue());
    }
}
