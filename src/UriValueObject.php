<?php

declare(strict_types=1);

namespace Marvin255\ValueObject;

use Marvin255\ValueObject\Helper\URIHelper;
use Psr\Http\Message\UriInterface;

/**
 * Immutable value object that represents a URI.
 *
 * @psalm-api
 *
 * @psalm-immutable
 */
final readonly class UriValueObject extends StringValueObject implements UriInterface
{
    /**
     * @throws \InvalidArgumentException if the provided string is not a valid URI
     */
    public function __construct(string $uri)
    {
        $trimmedUri = trim($uri);

        if (!URIHelper::isValidUri($trimmedUri)) {
            throw new \InvalidArgumentException("Unable to parse URI string: $uri");
        }

        parent::__construct($trimmedUri);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getScheme(): string
    {
        return URIHelper::extractStringPart($this->getValue(), \PHP_URL_SCHEME);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAuthority(): string
    {
        $authority = $this->getHost();

        if ($this->getUserInfo() !== '') {
            $authority = "{$this->getUserInfo()}@{$authority}";
        }

        if ($this->getPort() !== null) {
            $authority .= ":{$this->getPort()}";
        }

        return $authority;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUserInfo(): string
    {
        $userInfo = URIHelper::extractStringPart($this->getValue(), \PHP_URL_USER);

        $password = URIHelper::extractStringPart($this->getValue(), \PHP_URL_PASS);
        if ($password !== '') {
            $userInfo .= ":{$password}";
        }

        return $userInfo;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getHost(): string
    {
        return URIHelper::extractStringPart($this->getValue(), \PHP_URL_HOST);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPort(): ?int
    {
        return URIHelper::extractIntPart($this->getValue(), \PHP_URL_PORT);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPath(): string
    {
        return URIHelper::extractStringPart($this->getValue(), \PHP_URL_PATH);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getQuery(): string
    {
        return URIHelper::extractStringPart($this->getValue(), \PHP_URL_QUERY);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFragment(): string
    {
        return URIHelper::extractStringPart($this->getValue(), \PHP_URL_FRAGMENT);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withScheme(string $scheme): UriInterface
    {
        $newUri = URIHelper::replaceURIParts(
            $this->getValue(),
            [
                'scheme' => $scheme,
            ]
        );

        return new self($newUri);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $newUri = URIHelper::replaceURIParts(
            $this->getValue(),
            [
                'user' => $user,
                'pass' => $password,
            ]
        );

        return new self($newUri);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withHost(string $host): UriInterface
    {
        $newUri = URIHelper::replaceURIParts(
            $this->getValue(),
            [
                'host' => $host,
            ]
        );

        return new self($newUri);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withPort(?int $port): UriInterface
    {
        $newUri = URIHelper::replaceURIParts(
            $this->getValue(),
            [
                'port' => $port,
            ]
        );

        return new self($newUri);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withPath(string $path): UriInterface
    {
        $newUri = URIHelper::replaceURIParts(
            $this->getValue(),
            [
                'path' => $path,
            ]
        );

        return new self($newUri);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withQuery(string $query): UriInterface
    {
        $newUri = URIHelper::replaceURIParts(
            $this->getValue(),
            [
                'query' => $query,
            ]
        );

        return new self($newUri);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function withFragment(string $fragment): UriInterface
    {
        $newUri = URIHelper::replaceURIParts(
            $this->getValue(),
            [
                'fragment' => $fragment,
            ]
        );

        return new self($newUri);
    }
}
