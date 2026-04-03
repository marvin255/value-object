<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use Marvin255\ValueObject\FloatValueObject;
use Marvin255\ValueObject\ValueObject;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class FloatValueObjectTest extends BaseCase
{
    public function testToString(): void
    {
        $valueObject = new FloatValueObject(123.45);

        $this->assertSame('123.45', (string) $valueObject);
    }

    public function testToStringZero(): void
    {
        $valueObject = new FloatValueObject(0.0);

        $this->assertSame('0', (string) $valueObject);
    }

    public function testToStringNegative(): void
    {
        $valueObject = new FloatValueObject(-99.99);

        $this->assertSame('-99.99', (string) $valueObject);
    }

    #[DataProvider('provideEquals')]
    public function testEquals(ValueObject $object1, ValueObject $object2, bool $expected): void
    {
        $this->assertSame($expected, $object1->equals($object2));
    }

    public static function provideEquals(): array
    {
        return [
            'identical values' => [
                'object1' => new FloatValueObject(50.5),
                'object2' => new FloatValueObject(50.5),
                'expected' => true,
            ],
            'values within delta' => [
                'object1' => new FloatValueObject(1.0),
                'object2' => new FloatValueObject(1.0 + 1e-10),
                'expected' => true,
            ],
            'different values' => [
                'object1' => new FloatValueObject(50.5),
                'object2' => new FloatValueObject(75.0),
                'expected' => false,
            ],
            'zeroes' => [
                'object1' => new FloatValueObject(0.0),
                'object2' => new FloatValueObject(0.0),
                'expected' => true,
            ],
            'negative values' => [
                'object1' => new FloatValueObject(-5.5),
                'object2' => new FloatValueObject(-5.5),
                'expected' => true,
            ],
            'different types' => [
                'object1' => new FloatValueObject(50.0),
                'object2' => new class() implements ValueObject {
                    #[\Override]
                    public function __toString(): string
                    {
                        return '50';
                    }

                    #[\Override]
                    public function equals(ValueObject $other): bool
                    {
                        return true;
                    }

                    #[\Override]
                    public function getValue(): float
                    {
                        return 50.0;
                    }
                },
                'expected' => false,
            ],
        ];
    }

    public function testGetValue(): void
    {
        $valueObject = new FloatValueObject(42.75);

        $this->assertSame(42.75, $valueObject->getValue());
    }

    public function testGetValueNegative(): void
    {
        $valueObject = new FloatValueObject(-123.456);

        $this->assertSame(-123.456, $valueObject->getValue());
    }

    public function testDeltaComparisonWithinTolerance(): void
    {
        $value1 = new FloatValueObject(0.1 + 0.2);
        $value2 = new FloatValueObject(0.3);

        // Due to floating-point arithmetic, 0.1 + 0.2 !== 0.3 exactly
        // but they should be considered equal with delta
        $this->assertTrue($value1->equals($value2));
    }

    public function testDeltaComparisonOutsideTolerance(): void
    {
        $value1 = new FloatValueObject(1.0);
        $value2 = new FloatValueObject(1.0 + 1e-8);

        // This difference exceeds the delta of 1e-9
        $this->assertFalse($value1->equals($value2));
    }
}
