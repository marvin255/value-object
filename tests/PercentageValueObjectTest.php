<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use Marvin255\ValueObject\PercentageValueObject;
use Marvin255\ValueObject\ValueObject;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class PercentageValueObjectTest extends BaseCase
{
    #[DataProvider('invalidValuesProvider')]
    public function testInvalidValues(float $input): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new PercentageValueObject($input);
    }

    public static function invalidValuesProvider(): array
    {
        return [
            'negative number' => [-1.0],
            'greater than 100' => [101.0],
            'far above 100' => [1000.0],
        ];
    }

    public function testToString(): void
    {
        $valueObject = new PercentageValueObject(50.5);

        $this->assertSame('50.5', (string) $valueObject);
    }

    #[DataProvider('provideEquals')]
    public function testEquals(ValueObject $object1, ValueObject $object2, bool $expected): void
    {
        $this->assertSame($expected, $object1->equals($object2));
    }

    public static function provideEquals(): array
    {
        return [
            'zeroes' => [
                'object1' => new PercentageValueObject(0.0),
                'object2' => new PercentageValueObject(0.0),
                'expected' => true,
            ],
            'same values' => [
                'object1' => new PercentageValueObject(50.5),
                'object2' => new PercentageValueObject(50.5),
                'expected' => true,
            ],
            'different values' => [
                'object1' => new PercentageValueObject(50.5),
                'object2' => new PercentageValueObject(75.0),
                'expected' => false,
            ],
            'different types' => [
                'object1' => new PercentageValueObject(50.0),
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
        $valueObject = new PercentageValueObject(75.5);

        $this->assertSame(75.5, $valueObject->getValue());
    }

    public function testBoundaryValues(): void
    {
        $min = new PercentageValueObject(0.0);
        $max = new PercentageValueObject(100.0);

        $this->assertSame(0.0, $min->getValue());
        $this->assertSame(100.0, $max->getValue());
    }

    public function testDecimalValues(): void
    {
        $valueObject = new PercentageValueObject(33.33);

        $this->assertSame(33.33, $valueObject->getValue());
        $this->assertSame('33.33', (string) $valueObject);
    }

    #[DataProvider('provideGetComplimentary')]
    public function testGetComplimentary(float $input, float $expected): void
    {
        $valueObject = new PercentageValueObject($input);
        $result = $valueObject->getComplimentary();

        $this->assertInstanceOf(PercentageValueObject::class, $result);
        $this->assertEqualsWithDelta($expected, $result->getValue(), 1e-9);
    }

    public static function provideGetComplimentary(): array
    {
        return [
            'integer zero' => ['input' => 0, 'expected' => 100.0],
            'decimal zero' => ['input' => 0.0, 'expected' => 100.0],
            'integer between' => ['input' => 50, 'expected' => 50.0],
            'decimal between' => ['input' => 33.33, 'expected' => 66.67],
            'integer hundred' => ['input' => 100, 'expected' => 0.0],
            'decimal hundred' => ['input' => 100.0, 'expected' => 0.0],
        ];
    }

    #[DataProvider('provideApplyToNumber')]
    public function testApplyToNumber(float $percentage, float $number, float $expected): void
    {
        $valueObject = new PercentageValueObject($percentage);
        $result = $valueObject->applyToNumber($number);

        $this->assertEqualsWithDelta($expected, $result, 1e-9);
    }

    public static function provideApplyToNumber(): array
    {
        return [
            'zero percent of hundred' => ['percentage' => 0.0, 'number' => 100.0, 'expected' => 0.0],
            'fifty percent of hundred' => ['percentage' => 50.0, 'number' => 100.0, 'expected' => 50.0],
            'hundred percent of hundred' => ['percentage' => 100.0, 'number' => 100.0, 'expected' => 100.0],
            'thirty-three percent of hundred' => ['percentage' => 33.33, 'number' => 100.0, 'expected' => 33.33],
            'fifty percent of two hundred' => ['percentage' => 50.0, 'number' => 200.0, 'expected' => 100.0],
            'percent of negative number' => ['percentage' => 50.0, 'number' => -100.0, 'expected' => -50.0],
            'percent of decimal' => ['percentage' => 25.5, 'number' => 10.5, 'expected' => 2.6775],
        ];
    }
}
