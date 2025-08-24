<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Tests\Helper;

use Marvin255\ValueObject\Helper\URIHelper;
use Marvin255\ValueObject\Tests\BaseCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @internal
 */
final class URIHelperTest extends BaseCase
{
    #[DataProvider('provideIsValidUri')]
    public function testIsValidUri(string $uri, bool $expected): void
    {
        $this->assertSame($expected, URIHelper::isValidUri($uri));
    }

    public static function provideIsValidUri(): array
    {
        return [
            'contains spaces' => [
                'uri' => 'https://github com',
                'expected' => false,
            ],
            'contains EOF' => [
                'uri' => "https://github\ncom",
                'expected' => false,
            ],
            'only schema' => [
                'uri' => 'https://',
                'expected' => false,
            ],
            'incorrect port' => [
                'uri' => 'https://test.test:qwe',
                'expected' => false,
            ],
            'user without host' => [
                'uri' => 'https://test:test',
                'expected' => false,
            ],
            'empty string' => [
                'uri' => '',
                'expected' => true,
            ],
            'full DSL URI' => [
                'uri' => 'postgresql://symfony:symfonypass@postgres:5432/symfony?serverVersion=17&charset=utf8',
                'expected' => true,
            ],
            'related web URL no slash in the beginning' => [
                'uri' => 'example.com/some-path',
                'expected' => true,
            ],
            'related web URL with slash in the beginning' => [
                'uri' => '/some-path',
                'expected' => true,
            ],
            'simple web URL' => [
                'uri' => 'https://example.com/some-path',
                'expected' => true,
            ],
        ];
    }

    #[DataProvider('provideExtractStringPart')]
    public function testExtractStringPart(string $uri, int $part, string $expected): void
    {
        $this->assertSame($expected, URIHelper::extractStringPart($uri, $part));
    }

    public static function provideExtractStringPart(): array
    {
        return [
            'existed part' => [
                'uri' => 'https://test.com/path?query=1#fragment',
                'part' => \PHP_URL_HOST,
                'expected' => 'test.com',
            ],
            'not existed part' => [
                'uri' => 'https://test.com/path?query=1#fragment',
                'part' => \PHP_URL_USER,
                'expected' => '',
            ],
            'empty string' => [
                'uri' => '',
                'part' => \PHP_URL_HOST,
                'expected' => '',
            ],
        ];
    }

    #[DataProvider('provideExtractIntPart')]
    public function testExtractIntPart(string $uri, int $part, ?int $expected): void
    {
        $this->assertSame($expected, URIHelper::extractIntPart($uri, $part));
    }

    public static function provideExtractIntPart(): array
    {
        return [
            'existed part' => [
                'uri' => 'https://test.com:1234/path?query=1#fragment',
                'part' => \PHP_URL_PORT,
                'expected' => 1234,
            ],
            'not existed part' => [
                'uri' => 'https://test.com/path?query=1#fragment',
                'part' => \PHP_URL_PORT,
                'expected' => null,
            ],
            'empty string' => [
                'uri' => '',
                'part' => \PHP_URL_PORT,
                'expected' => null,
            ],
        ];
    }

    /**
     * @param array<string, string|int|null> $replacements
     */
    #[DataProvider('provideReplaceURIParts')]
    public function testReplaceURIParts(string $uri, array $replacements, string $expected): void
    {
        $this->assertSame($expected, URIHelper::replaceURIParts($uri, $replacements));
    }

    public static function provideReplaceURIParts(): array
    {
        return [
            'replace part in the full URI' => [
                'uri' => 'https://user:password@test.com:123/marvin255/value-object?a=1&b=2#fragment',
                'replacements' => [
                    'host' => 'example.com',
                ],
                'expected' => 'https://user:password@example.com:123/marvin255/value-object?a=1&b=2#fragment',
            ],
            'add part to the partial URI' => [
                'uri' => 'marvin255/value-object',
                'replacements' => [
                    'scheme' => 'https',
                    'host' => 'example.com',
                ],
                'expected' => 'https://example.com/marvin255/value-object',
            ],
            'add part to the partial URI without domain' => [
                'uri' => 'marvin255/value-object',
                'replacements' => [
                    'scheme' => 'https',
                ],
                'expected' => 'https://marvin255/value-object',
            ],
            'add part to the partial URI without domain and with leading slash' => [
                'uri' => '/marvin255/value-object',
                'replacements' => [
                    'scheme' => 'https',
                ],
                'expected' => 'https://marvin255/value-object',
            ],
            'empty string' => [
                'uri' => '',
                'replacements' => [
                    'scheme' => 'https',
                    'host' => 'example.com',
                    'path' => '/path',
                ],
                'expected' => 'https://example.com/path',
            ],
        ];
    }
}
