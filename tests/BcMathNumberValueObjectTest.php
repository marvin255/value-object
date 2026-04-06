<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use BcMath\Number;
use Marvin255\ValueObject\BcMathNumberValueObject;
use Marvin255\ValueObject\ValueObject;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class BcMathNumberValueObjectTest extends BaseCase
{
    #[DataProvider('provideConstruct')]
    public function testConstruct(Number|string $value, string $expected): void
    {
        $valueObject = new BcMathNumberValueObject($value);

        $this->assertSame($expected, $valueObject->getValue()->__toString());
    }

    public static function provideConstruct(): array
    {
        return [
            'from Number' => [
                'value' => new Number('123.456'),
                'expected' => '123.456',
            ],
            'from string' => [
                'value' => '789.012',
                'expected' => '789.012',
            ],
            'from string with leading zeros' => [
                'value' => '000123.456',
                'expected' => '123.456',
            ],
        ];
    }

    public function testGetValue(): void
    {
        $number = new Number('999.999');
        $valueObject = new BcMathNumberValueObject($number);

        $this->assertSame($number, $valueObject->getValue());
    }

    public function testToString(): void
    {
        $number = new Number('42.42');
        $valueObject = new BcMathNumberValueObject($number);

        $this->assertSame('42.42', (string) $valueObject);
    }

    #[DataProvider('provideEquals')]
    public function testEquals(ValueObject $object1, ValueObject $object2, bool $expected): void
    {
        $this->assertSame($expected, $object1->equals($object2));
    }

    public static function provideEquals(): array
    {
        return [
            'same strings' => [
                'object1' => new BcMathNumberValueObject('123.123123'),
                'object2' => new BcMathNumberValueObject('123.123123'),
                'expected' => true,
            ],
            'same numbers' => [
                'object1' => new BcMathNumberValueObject(new Number('123.123123')),
                'object2' => new BcMathNumberValueObject(new Number('123.123123')),
                'expected' => true,
            ],
            'different strings' => [
                'object1' => new BcMathNumberValueObject('123.123123'),
                'object2' => new BcMathNumberValueObject('456.456456'),
                'expected' => false,
            ],
            'different numbers' => [
                'object1' => new BcMathNumberValueObject(new Number('123.123123')),
                'object2' => new BcMathNumberValueObject(new Number('456.456456')),
                'expected' => false,
            ],
            'different types' => [
                'object1' => new BcMathNumberValueObject('123'),
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
                    public function getValue(): Number
                    {
                        return new Number('123');
                    }
                },
                'expected' => false,
            ],
        ];
    }

    public function testArbitraryPrecision(): void
    {
        $number = new Number('0.1');
        $valueObject = new BcMathNumberValueObject($number);

        $this->assertSame('0.1', (string) $valueObject);
    }
}
