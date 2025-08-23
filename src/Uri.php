<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

use Psr\Http\Message\UriInterface;

/**
 * Immutable value object that represents a URI (RFC 3986).
 *
 * This implementation is read-only and does not support modification methods.
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final class Uri implements UriInterface, ValueObject
{
    private string $scheme;

    private string $user;

    private string $pass;

    private string $host;

    private ?int $port;

    private string $path;

    private string $query;

    private string $fragment;

    public function __construct(string $uri)
    {
        if (!$this->isValidUrl($uri)) {
            throw new \InvalidArgumentException("Unable to parse URI string: $uri");
        }

        $parts = parse_url($uri);

        $this->scheme = $parts['scheme'] ?? '';
        $this->user = $parts['user'] ?? '';
        $this->pass = $parts['pass'] ?? '';
        $this->host = $parts['host'] ?? '';
        $this->port = $parts['port'] ?? null;
        $this->path = $parts['path'] ?? '';
        $this->query = $parts['query'] ?? '';
        $this->fragment = $parts['fragment'] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAuthority(): string
    {
        $authority = $this->host;
        if ($this->getUserInfo() !== '') {
            $authority = $this->getUserInfo() . '@' . $authority;
        }
        if ($this->port !== null) {
            $authority .= ":{$this->port}";
        }

        return $authority;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUserInfo(): string
    {
        $userInfo = $this->user;
        if ($this->pass !== '') {
            $userInfo .= ":{$this->pass}";
        }

        return $userInfo;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withScheme(string $scheme): UriInterface
    {
        $newUri = clone $this;
        $newUri->scheme = $scheme;

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $newUri = clone $this;
        $newUri->user = $user;
        $newUri->pass = $password ?? '';

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withHost(string $host): UriInterface
    {
        $newUri = clone $this;
        $newUri->host = $host;

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withPort(?int $port): UriInterface
    {
        $newUri = clone $this;
        $newUri->port = $port;

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withPath(string $path): UriInterface
    {
        $newUri = clone $this;
        $newUri->path = $path;

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withQuery(string $query): UriInterface
    {
        $newUri = clone $this;
        $newUri->query = $query;

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withFragment(string $fragment): UriInterface
    {
        $newUri = clone $this;
        $newUri->fragment = $fragment;

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function __toString(): string
    {
        $uri = $this->scheme !== '' ? $this->scheme . '://' : '';
        $uri .= $this->getAuthority();
        $uri .= $this->path;
        $uri .= $this->query !== '' ? "?{$this->query}" : '';
        $uri .= $this->fragment !== '' ? "#{$this->fragment}" : '';

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->__toString() === $other->__toString();
    }

    private function isValidUrl(string $url): bool
    {
        if ($url === '') {
            return true;
        }

        if (parse_url($url, \PHP_URL_SCHEME) === null) {
            $url = 'http://' . ltrim($url, '/');
        }

        return filter_var($url, \FILTER_VALIDATE_URL) !== false;
    }
}
