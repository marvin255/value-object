<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests;

use Marvin255\ValueObject\UriValueObject;
use Marvin255\ValueObject\ValueObject;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class UriValueObjectTest extends BaseCase
{
    #[DataProvider('provideInvalidUri')]
    public function testInvalidUri(string $uri): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($uri);

        new UriValueObject($uri);
    }

    public static function provideInvalidUri(): array
    {
        return [
            'contains spaces' => [
                'https://github com',
            ],
            'contains EOF' => [
                "https://github\ncom",
            ],
            'only schema' => [
                'https://',
            ],
            'incorrect port' => [
                'https://test.test:qwe',
            ],
            'user without host' => [
                'https://test:test',
            ],
        ];
    }

    #[DataProvider('provideParamsGetters')]
    public function testParamsGetters(string $uri, array $expected): void
    {
        $obj = new UriValueObject($uri);

        $this->assertSame($expected['scheme'], $obj->getScheme());
        $this->assertSame($expected['userInfo'], $obj->getUserInfo());
        $this->assertSame($expected['authority'], $obj->getAuthority());
        $this->assertSame($expected['host'], $obj->getHost());
        $this->assertSame($expected['port'], $obj->getPort());
        $this->assertSame($expected['path'], $obj->getPath());
        $this->assertSame($expected['query'], $obj->getQuery());
        $this->assertSame($expected['fragment'], $obj->getFragment());
        $this->assertSame($expected['full'], $obj->getValue());
    }

    public static function provideParamsGetters(): array
    {
        return [
            'simple web URL' => [
                'uri' => 'https://test.com/marvin255/value-object',
                'expected' => [
                    'scheme' => 'https',
                    'userInfo' => '',
                    'authority' => 'test.com',
                    'host' => 'test.com',
                    'port' => null,
                    'path' => '/marvin255/value-object',
                    'query' => '',
                    'fragment' => '',
                    'full' => 'https://test.com/marvin255/value-object',
                ],
            ],
            'URL with extra spaces' => [
                'uri' => " \t\n https://test.com/marvin255/value-object ",
                'expected' => [
                    'scheme' => 'https',
                    'userInfo' => '',
                    'authority' => 'test.com',
                    'host' => 'test.com',
                    'port' => null,
                    'path' => '/marvin255/value-object',
                    'query' => '',
                    'fragment' => '',
                    'full' => 'https://test.com/marvin255/value-object',
                ],
            ],
            'related web URL' => [
                'uri' => '/marvin255/value-object',
                'expected' => [
                    'scheme' => '',
                    'userInfo' => '',
                    'authority' => '',
                    'host' => '',
                    'port' => null,
                    'path' => '/marvin255/value-object',
                    'query' => '',
                    'fragment' => '',
                    'full' => '/marvin255/value-object',
                ],
            ],
            'related web URL no slash in the beginning' => [
                'uri' => 'marvin255/value-object',
                'expected' => [
                    'scheme' => '',
                    'userInfo' => '',
                    'authority' => '',
                    'host' => '',
                    'port' => null,
                    'path' => 'marvin255/value-object',
                    'query' => '',
                    'fragment' => '',
                    'full' => 'marvin255/value-object',
                ],
            ],
            'full URI' => [
                'uri' => 'https://user:password@test.com:123/marvin255/value-object?a=1&b=2#fragment',
                'expected' => [
                    'scheme' => 'https',
                    'userInfo' => 'user:password',
                    'authority' => 'user:password@test.com:123',
                    'host' => 'test.com',
                    'port' => 123,
                    'path' => '/marvin255/value-object',
                    'query' => 'a=1&b=2',
                    'fragment' => 'fragment',
                    'full' => 'https://user:password@test.com:123/marvin255/value-object?a=1&b=2#fragment',
                ],
            ],
            'full DSL URI' => [
                'uri' => 'postgresql://symfony:symfonypass@postgres:5432/symfony?serverVersion=17&charset=utf8',
                'expected' => [
                    'scheme' => 'postgresql',
                    'userInfo' => 'symfony:symfonypass',
                    'authority' => 'symfony:symfonypass@postgres:5432',
                    'host' => 'postgres',
                    'port' => 5432,
                    'path' => '/symfony',
                    'query' => 'serverVersion=17&charset=utf8',
                    'fragment' => '',
                    'full' => 'postgresql://symfony:symfonypass@postgres:5432/symfony?serverVersion=17&charset=utf8',
                ],
            ],
            'empty string' => [
                'uri' => '',
                'expected' => [
                    'scheme' => '',
                    'userInfo' => '',
                    'authority' => '',
                    'host' => '',
                    'port' => null,
                    'path' => '',
                    'query' => '',
                    'fragment' => '',
                    'full' => '',
                ],
            ],
        ];
    }

    public function testWithScheme(): void
    {
        $obj = new UriValueObject('test.com/path');
        $new = $obj->withScheme('https');

        $this->assertNotSame($obj, $new);
        $this->assertSame('https://test.com/path', $new->getValue());
    }

    public function testWithUserInfo(): void
    {
        $obj = new UriValueObject('https://test.com/path');
        $new = $obj->withUserInfo('user', 'password');

        $this->assertNotSame($obj, $new);
        $this->assertSame('https://user:password@test.com/path', $new->getValue());
    }

    public function testWithHost(): void
    {
        $obj = new UriValueObject('https://test.com/path');
        $new = $obj->withHost('example.com');

        $this->assertNotSame($obj, $new);
        $this->assertSame('https://example.com/path', $new->getValue());
    }

    public function testWithPort(): void
    {
        $obj = new UriValueObject('https://test.com/path');
        $new = $obj->withPort(1234);

        $this->assertNotSame($obj, $new);
        $this->assertSame('https://test.com:1234/path', $new->getValue());
    }

    public function testWithPath(): void
    {
        $obj = new UriValueObject('https://test.com/path');
        $new = $obj->withPath('/new-path');

        $this->assertNotSame($obj, $new);
        $this->assertSame('https://test.com/new-path', $new->getValue());
    }

    public function testWithQuery(): void
    {
        $obj = new UriValueObject('https://test.com/path');
        $new = $obj->withQuery('a=1&b=2');

        $this->assertNotSame($obj, $new);
        $this->assertSame('https://test.com/path?a=1&b=2', $new->getValue());
    }

    public function testWithFragment(): void
    {
        $obj = new UriValueObject('https://test.com/path');
        $new = $obj->withFragment('fragment');

        $this->assertNotSame($obj, $new);
        $this->assertSame('https://test.com/path#fragment', $new->getValue());
    }

    public function testToString(): void
    {
        $uri = 'https://test.com/path?a=1&b=2#fragment';
        $obj = new UriValueObject($uri);

        $this->assertSame($uri, (string) $obj);
    }

    #[DataProvider('provideEquals')]
    public function testEquals(UriValueObject $object1, ValueObject $object2, bool $expected): void
    {
        $result = $object1->equals($object2);

        $this->assertSame($expected, $result);
    }

    public static function provideEquals(): array
    {
        return [
            'equal URIs' => [
                'object1' => new UriValueObject('https://test.com/path'),
                'object2' => new UriValueObject('https://test.com/path'),
                'expected' => true,
            ],
            'not equal URIs' => [
                'object1' => new UriValueObject('https://test.com/path'),
                'object2' => new UriValueObject('https://test.com/otherPath'),
                'expected' => false,
            ],
            'different object type' => [
                'object1' => new UriValueObject('https://test.com/path'),
                'object2' => new class() implements ValueObject {
                    #[\Override]
                    public function __toString(): string
                    {
                        return 'https://test.com/path';
                    }

                    #[\Override]
                    public function equals(ValueObject $other): bool
                    {
                        return true;
                    }

                    #[\Override]
                    public function getValue(): string
                    {
                        return 'https://test.com/path';
                    }
                },
                'expected' => false,
            ],
        ];
    }
}
