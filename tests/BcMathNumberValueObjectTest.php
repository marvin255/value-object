<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use BcMath\Number;
use Marvin255\ValueObject\BcMathNumberValueObject;
use Marvin255\ValueObject\StringValueObject;

/**
 * @internal
 */
final class BcMathNumberValueObjectTest extends BaseCase
{
    public function testCanCreateFromNumber(): void
    {
        $number = new Number('123.456789');
        $valueObject = new BcMathNumberValueObject($number);

        self::assertInstanceOf(BcMathNumberValueObject::class, $valueObject);
    }

    public function testGetValue(): void
    {
        $number = new Number('999.999');
        $valueObject = new BcMathNumberValueObject($number);

        self::assertSame($number, $valueObject->getValue());
    }

    public function testToString(): void
    {
        $number = new Number('42.42');
        $valueObject = new BcMathNumberValueObject($number);

        self::assertSame('42.42', (string) $valueObject);
    }

    public function testEqualsWithSameValue(): void
    {
        $valueObject1 = new BcMathNumberValueObject(new Number('100.50'));
        $valueObject2 = new BcMathNumberValueObject(new Number('100.50'));

        self::assertTrue($valueObject1->equals($valueObject2));
    }

    public function testEqualsWithDifferentValue(): void
    {
        $valueObject1 = new BcMathNumberValueObject(new Number('100.50'));
        $valueObject2 = new BcMathNumberValueObject(new Number('100.51'));

        self::assertFalse($valueObject1->equals($valueObject2));
    }

    public function testNotEqualsWithDifferentType(): void
    {
        $valueObject = new BcMathNumberValueObject(new Number('100'));
        $stringObject = new StringValueObject('100');

        self::assertFalse($valueObject->equals($stringObject));
    }

    public function testArbitraryPrecision(): void
    {
        $number = new Number('0.1');
        $valueObject = new BcMathNumberValueObject($number);

        self::assertSame('0.1', (string) $valueObject);
    }
}
