<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use Marvin255\ValueObject\Email;
use Marvin255\ValueObject\ValueObject;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class EmailTest extends BaseCase
{
    public function testEmail(): void
    {
        $obj = new Email('test@test.test');

        $this->assertSame('test@test.test', (string) $obj);
        $this->assertSame('test', $obj->getLocalPart());
        $this->assertSame('test.test', $obj->getDomain());
    }

    #[DataProvider('provideInvalidEmails')]
    public function testInvalidEmail(string $email): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid email address: {$email}");
        new Email($email);
    }

    public static function provideInvalidEmails(): array
    {
        return [
            [''],
            [' '],
            ['test'],
            ['test@'],
            ['@test'],
            ['test@test'],
            ['test@.com'],
            ['test@test.'],
            ['test@@test.com'],
            ['test@test..com'],
            ['test@.test.com'],
            ['test  @test.com'],
            ['test@te st.com'],
            ['test@test.c om'],
        ];
    }

    #[DataProvider('provideEquals')]
    public function testEquals(Email $object1, ValueObject $object2, bool $expected): void
    {
        $result = $object1->equals($object2);

        $this->assertSame($expected, $result);
    }

    public static function provideEquals(): array
    {
        return [
            'equal emails' => [
                'object1' => new Email('test@test.test'),
                'object2' => new Email('test@test.test'),
                'expected' => true,
            ],
            'equal emails in different cases' => [
                'object1' => new Email('TEST@test.test'),
                'object2' => new Email('test@TEST.test'),
                'expected' => true,
            ],
            'not equal emails' => [
                'object1' => new Email('test@test.test'),
                'object2' => new Email('test@test.test1'),
                'expected' => false,
            ],
            'different object type' => [
                'object1' => new Email('test@test.test'),
                'object2' => new class() implements ValueObject {
                    #[\Override]
                    public function __toString(): string
                    {
                        return 'test@test.test';
                    }

                    #[\Override]
                    public function equals(ValueObject $other): bool
                    {
                        return true;
                    }
                },
                'expected' => false,
            ],
        ];
    }
}
