<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use Marvin255\ValueObject\StringValueObject;
use Marvin255\ValueObject\ValueObject;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class StringValueObjectTest extends BaseCase
{
    public function testToString(): void
    {
        $strintgValue = 'test string';
        $valueObject = new StringValueObject($strintgValue);

        $this->assertSame($strintgValue, (string) $valueObject);
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
                'object1' => new StringValueObject('test'),
                'object2' => new StringValueObject('test'),
                'expected' => true,
            ],
            'different values' => [
                'object1' => new StringValueObject(''),
                'object2' => new StringValueObject('test'),
                'expected' => false,
            ],
            'different types' => [
                'object1' => new StringValueObject('test'),
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
        $strintgValue = 'test string';
        $valueObject = new StringValueObject($strintgValue);

        $this->assertSame($strintgValue, $valueObject->getValue());
    }
}
