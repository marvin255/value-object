<?php

declare(strict_types=1);

namespace Marvin255\ValueObject\Helper;

/**
 * Helper class for URI operations.
 *
 * @internal
 */
final class URIHelper
{
    /**
     * @psalm-suppress UnusedConstructor
     */
    private function __construct()
    {
    }

    /**
     * Check if the given string is a valid URI.
     *
     * @psalm-pure
     *
     * @psalm-immutable
     */
    public static function isValidUri(string $uri): bool
    {
        if ($uri === '') {
            return true;
        }

        if (parse_url($uri, \PHP_URL_SCHEME) === null) {
            $uri = 'http://' . ltrim($uri, '/');
        }

        return filter_var($uri, \FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Extracts and returns a string part of the URI.
     *
     * @psalm-pure
     *
     * @psalm-immutable
     */
    public static function extractStringPart(string $uri, int $component): string
    {
        $part = parse_url($uri, $component);

        return \is_string($part) ? $part : '';
    }

    /**
     * Extracts and returns an integer part of the URI or null if not present.
     *
     * @psalm-pure
     *
     * @psalm-immutable
     */
    public static function extractIntPart(string $uri, int $component): ?int
    {
        $part = parse_url($uri, $component);

        return \is_int($part) ? $part : null;
    }

    /**
     * Build a URI string from its parts, replacing specified parts.
     *
     * @param array<string, string|int|null> $replacements
     *
     * @psalm-pure
     *
     * @psalm-immutable
     */
    public static function replaceURIParts(string $uri, array $replacements): string
    {
        $parts = parse_url($uri);
        $parts = $parts === false ? [] : $parts;

        $newUri = '';

        $scheme = self::getURIParamOrReplacement('scheme', $parts, $replacements);
        if ($scheme !== '') {
            $newUri = $scheme . '://';
        }

        $user = self::getURIParamOrReplacement('user', $parts, $replacements);
        if ($user !== '') {
            $newUri .= $user;
            $pass = self::getURIParamOrReplacement('pass', $parts, $replacements);
            if ($pass !== '') {
                $newUri .= ':' . $pass;
            }
            $newUri .= '@';
        }

        $host = self::getURIParamOrReplacement('host', $parts, $replacements);
        if ($host !== '') {
            $newUri .= $host;
        }

        $port = self::getURIParamOrReplacement('port', $parts, $replacements);
        if ($port !== '') {
            $newUri .= ':' . $port;
        }

        $path = self::getURIParamOrReplacement('path', $parts, $replacements);
        if ($path !== '') {
            if (str_ends_with($newUri, '/') && str_starts_with($path, '/')) {
                $newUri .= ltrim($path, '/');
            } elseif (!str_ends_with($newUri, '/') && !str_starts_with($path, '/')) {
                $newUri .= '/' . $path;
            } else {
                $newUri .= $path;
            }
        }

        $query = self::getURIParamOrReplacement('query', $parts, $replacements);
        if ($query !== '') {
            $newUri .= '?' . $query;
        }

        $fragment = self::getURIParamOrReplacement('fragment', $parts, $replacements);
        if ($fragment !== '') {
            $newUri .= '#' . $fragment;
        }

        return $newUri;
    }

    /**
     * Get a URI part from the parsed parts or from the replacements if provided.
     *
     * @psalm-pure
     *
     * @psalm-immutable
     */
    private static function getURIParamOrReplacement(string $param, array $parts, array $replacements): string
    {
        if (\array_key_exists($param, $replacements)) {
            return (string) $replacements[$param];
        }

        return (string) ($parts[$param] ?? '');
    }
}
