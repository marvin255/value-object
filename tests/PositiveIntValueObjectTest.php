<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use Marvin255\ValueObject\PositiveIntValueObject;
use Marvin255\ValueObject\ValueObject;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class PositiveIntValueObjectTest extends BaseCase
{
    #[DataProvider('invalidValuesProvider')]
    public function testInvalidValues(int $input): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new PositiveIntValueObject($input);
    }

    public static function invalidValuesProvider(): array
    {
        return [
            'zero' => [0],
            'negative number' => [-1],
        ];
    }

    public function testToString(): void
    {
        $valueObject = new PositiveIntValueObject(123);

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
            'same values' => [
                'object1' => new PositiveIntValueObject(5),
                'object2' => new PositiveIntValueObject(5),
                'expected' => true,
            ],
            'different values' => [
                'object1' => new PositiveIntValueObject(5),
                'object2' => new PositiveIntValueObject(10),
                'expected' => false,
            ],
            'different types' => [
                'object1' => new PositiveIntValueObject(123),
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
        $valueObject = new PositiveIntValueObject(42);

        $this->assertSame(42, $valueObject->getValue());
    }
}
