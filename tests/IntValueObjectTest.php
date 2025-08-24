<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use Marvin255\ValueObject\IntValueObject;
use Marvin255\ValueObject\ValueObject;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class IntValueObjectTest extends BaseCase
{
    public function testToString(): void
    {
        $valueObject = new IntValueObject(123);

        $this->assertSame('123', (string) $valueObject);
    }

    #[DataProvider('provideEquals')]
    public function testEquals(ValueObject $object1, ValueObject $object2, bool $expected): void
    {
        $this->assertSame($expected, $object1->equals($object2));
    }

    public static function provideEquals(): array
    {
        return [
            'negative numbers' => [
                'object1' => new IntValueObject(-123),
                'object2' => new IntValueObject(-123),
                'expected' => true,
            ],
            'zeroes' => [
                'object1' => new IntValueObject(0),
                'object2' => new IntValueObject(0),
                'expected' => true,
            ],
            'same values' => [
                'object1' => new IntValueObject(5),
                'object2' => new IntValueObject(5),
                'expected' => true,
            ],
            'different values' => [
                'object1' => new IntValueObject(5),
                'object2' => new IntValueObject(10),
                'expected' => false,
            ],
            'different types' => [
                'object1' => new IntValueObject(123),
                'object2' => new class() implements ValueObject {
                    #[\Override]
                    public function __toString(): string
                    {
                        return '123';
                    }

                    #[\Override]
                    public function equals(ValueObject $other): bool
                    {
                        return true;
                    }

                    #[\Override]
                    public function getValue(): int
                    {
                        return 123;
                    }
                },
                'expected' => false,
            ],
        ];
    }

    public function testGetValue(): void
    {
        $valueObject = new IntValueObject(42);

        $this->assertSame(42, $valueObject->getValue());
    }
}
