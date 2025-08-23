<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests\Uri;

use Marvin255\ValueObject\Tests\BaseCase;
use Marvin255\ValueObject\Uri\Uri;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class UriTest extends BaseCase
{
    #[DataProvider('provideParamsGetters')]
    public function testParamsGetters(string $uri, array $expected): void
    {
        $obj = new Uri($uri);

        $this->assertSame($expected['scheme'], $obj->getScheme());
        $this->assertSame($expected['userInfo'], $obj->getUserInfo());
        $this->assertSame($expected['authority'], $obj->getAuthority());
        $this->assertSame($expected['host'], $obj->getHost());
        $this->assertSame($expected['port'], $obj->getPort());
        $this->assertSame($expected['path'], $obj->getPath());
        $this->assertSame($expected['query'], $obj->getQuery());
        $this->assertSame($expected['fragment'], $obj->getFragment());
        $this->assertSame($expected['full'], (string) $obj);
    }

    public static function provideParamsGetters(): array
    {
        return [
            'simple web URL' => [
                'uri' => 'https://github.com/marvin255/value-object',
                'expected' => [
                    'scheme' => 'https',
                    'userInfo' => '',
                    'authority' => 'github.com',
                    'host' => 'github.com',
                    'port' => null,
                    'path' => '/marvin255/value-object',
                    'query' => '',
                    'fragment' => '',
                    'full' => 'https://github.com/marvin255/value-object',
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
                'uri' => 'https://user:password@github.com:123/marvin255/value-object?a=1&b=2#fragment',
                'expected' => [
                    'scheme' => 'https',
                    'userInfo' => 'user:password',
                    'authority' => 'user:password@github.com:123',
                    'host' => 'github.com',
                    'port' => 123,
                    'path' => '/marvin255/value-object',
                    'query' => 'a=1&b=2',
                    'fragment' => 'fragment',
                    'full' => 'https://user:password@github.com:123/marvin255/value-object?a=1&b=2#fragment',
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

    #[DataProvider('provideInvalidUri')]
    public function testInvalidUri(string $uri): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($uri);

        new Uri($uri);
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
}
