<?php

namespace Scriptmancer\Support;

class Domain
{
    /**
     * The underlying URL/domain value.
     *
     * @var string
     */
    protected $value;

    /**
     * Create a new Domain instance.
     *
     * @param string $value
     */
    public function __construct(string $value = '')
    {
        $this->value = $value;
    }

    /**
     * Return the underlying URL/domain as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Get the underlying URL/domain value.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * Create a new Domain instance.
     *
     * @param string $value
     * @return static
     */
    public static function of(string $value): self
    {
        return new static($value);
    }

    /**
     * Determine if the current URL/domain is valid.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return Url::isValid($this->value);
    }

    /**
     * Get the scheme from the URL.
     *
     * @return string|null
     */
    public function scheme(): ?string
    {
        return Url::scheme($this->value);
    }

    /**
     * Get the host from the URL.
     *
     * @return string|null
     */
    public function host(): ?string
    {
        return Url::host($this->value);
    }

    /**
     * Get the port from the URL.
     *
     * @return int|null
     */
    public function port(): ?int
    {
        return Url::port($this->value);
    }

    /**
     * Get the path from the URL.
     *
     * @return string|null
     */
    public function path(): ?string
    {
        return Url::path($this->value);
    }

    /**
     * Get the query string from the URL.
     *
     * @return string|null
     */
    public function query(): ?string
    {
        return Url::query($this->value);
    }

    /**
     * Get the fragment from the URL.
     *
     * @return string|null
     */
    public function fragment(): ?string
    {
        return Url::fragment($this->value);
    }

    /**
     * Parse the query string from the URL into an array.
     *
     * @return array
     */
    public function parseQuery(): array
    {
        return Url::parseQuery($this->value);
    }

    /**
     * Add a query parameter to the URL.
     *
     * @param string $key
     * @param string $value
     * @return static
     */
    public function addQuery(string $key, string $value): self
    {
        return new static(Url::addQuery($this->value, $key, $value));
    }

    /**
     * Remove a query parameter from the URL.
     *
     * @param string $key
     * @return static
     */
    public function removeQuery(string $key): self
    {
        return new static(Url::removeQuery($this->value, $key));
    }

    /**
     * Determine if the URL is using HTTPS.
     *
     * @return bool
     */
    public function isHttps(): bool
    {
        return Url::isHttps($this->value);
    }

    /**
     * Convert the URL to HTTPS.
     *
     * @return static
     */
    public function toHttps(): self
    {
        return new static(Url::toHttps($this->value));
    }

    /**
     * Convert the URL to HTTP.
     *
     * @return static
     */
    public function toHttp(): self
    {
        return new static(Url::toHttp($this->value));
    }

    /**
     * Get the domain from the URL.
     *
     * @param bool $withSubdomain
     * @return string|null
     */
    public function domain(bool $withSubdomain = true): ?string
    {
        return Url::domain($this->value, $withSubdomain);
    }

    /**
     * Get the subdomain from the URL.
     *
     * @return string|null
     */
    public function subdomain(): ?string
    {
        return Url::subdomain($this->value);
    }

    /**
     * Get the top-level domain (TLD) from the URL.
     *
     * @return string|null
     */
    public function tld(): ?string
    {
        $host = $this->host();
        
        if (empty($host)) {
            return null;
        }
        
        $parts = explode('.', $host);
        return end($parts);
    }

    /**
     * Check if the domain is a subdomain.
     *
     * @return bool
     */
    public function isSubdomain(): bool
    {
        return $this->subdomain() !== null;
    }

    /**
     * Check if the domain has a specific TLD.
     *
     * @param string $tld
     * @return bool
     */
    public function hasTld(string $tld): bool
    {
        $currentTld = $this->tld();
        return $currentTld !== null && strtolower($currentTld) === strtolower($tld);
    }

    /**
     * Join a path to the current URL.
     *
     * @param string $path
     * @return static
     */
    public function join(string $path): self
    {
        $parts = Url::parse($this->value);
        
        if (isset($parts['path'])) {
            $parts['path'] = Url::join($parts['path'], $path);
        } else {
            $parts['path'] = '/' . ltrim($path, '/');
        }
        
        return new static(Url::build($parts));
    }

    /**
     * Replace the path of the current URL.
     *
     * @param string $path
     * @return static
     */
    public function withPath(string $path): self
    {
        $parts = Url::parse($this->value);
        $parts['path'] = '/' . ltrim($path, '/');
        
        return new static(Url::build($parts));
    }

    /**
     * Replace the query string of the current URL.
     *
     * @param array|string $query
     * @return static
     */
    public function withQuery($query): self
    {
        $parts = Url::parse($this->value);
        
        if (is_array($query)) {
            $parts['query'] = Url::buildQuery($query);
        } else {
            $parts['query'] = ltrim($query, '?');
        }
        
        return new static(Url::build($parts));
    }

    /**
     * Replace the fragment of the current URL.
     *
     * @param string $fragment
     * @return static
     */
    public function withFragment(string $fragment): self
    {
        $parts = Url::parse($this->value);
        $parts['fragment'] = ltrim($fragment, '#');
        
        return new static(Url::build($parts));
    }

    /**
     * Check if the URL is absolute (has a scheme).
     *
     * @return bool
     */
    public function isAbsolute(): bool
    {
        return $this->scheme() !== null;
    }

    /**
     * Check if the URL is relative (no scheme).
     *
     * @return bool
     */
    public function isRelative(): bool
    {
        return $this->scheme() === null;
    }

    /**
     * Convert a relative URL to an absolute URL by providing a base URL.
     *
     * @param string $baseUrl
     * @return static
     */
    public function makeAbsolute(string $baseUrl): self
    {
        // If already absolute, return as is
        if ($this->isAbsolute()) {
            return new static($this->value);
        }
        
        $baseParts = Url::parse($baseUrl);
        
        if (empty($baseParts['scheme']) || empty($baseParts['host'])) {
            throw new \InvalidArgumentException('Base URL must be absolute');
        }
        
        // If the URL starts with //, it's a protocol-relative URL
        if (strpos($this->value, '//') === 0) {
            return new static($baseParts['scheme'] . ':' . $this->value);
        }
        
        // If the URL starts with /, it's relative to the root
        if (strpos($this->value, '/') === 0) {
            $baseRoot = $baseParts['scheme'] . '://' . $baseParts['host'];
            if (!empty($baseParts['port'])) {
                $baseRoot .= ':' . $baseParts['port'];
            }
            return new static($baseRoot . $this->value);
        }
        
        // Otherwise, it's relative to the current path
        $basePath = '';
        if (!empty($baseParts['path'])) {
            $basePath = dirname($baseParts['path']);
            if ($basePath !== '/') {
                $basePath .= '/';
            }
        } else {
            $basePath = '/';
        }
        
        $baseRoot = $baseParts['scheme'] . '://' . $baseParts['host'];
        if (!empty($baseParts['port'])) {
            $baseRoot .= ':' . $baseParts['port'];
        }
        
        return new static($baseRoot . $basePath . $this->value);
    }

    /**
     * Remove the fragment from the URL.
     *
     * @return static
     */
    public function withoutFragment(): self
    {
        $parts = Url::parse($this->value);
        
        if (isset($parts['fragment'])) {
            unset($parts['fragment']);
        }
        
        return new static(Url::build($parts));
    }

    /**
     * Remove the query string from the URL.
     *
     * @return static
     */
    public function withoutQuery(): self
    {
        $parts = Url::parse($this->value);
        
        if (isset($parts['query'])) {
            unset($parts['query']);
        }
        
        return new static(Url::build($parts));
    }

    /**
     * Get the basename of the path.
     *
     * @return string|null
     */
    public function basename(): ?string
    {
        $path = $this->path();
        
        if (empty($path) || $path === '/') {
            return null;
        }
        
        return basename($path);
    }

    /**
     * Get the directory name of the path.
     *
     * @return string|null
     */
    public function dirname(): ?string
    {
        $path = $this->path();
        
        if (empty($path) || $path === '/') {
            return null;
        }
        
        return dirname($path);
    }

    /**
     * Get the file extension of the path.
     *
     * @return string|null
     */
    public function extension(): ?string
    {
        $basename = $this->basename();
        
        if (empty($basename)) {
            return null;
        }
        
        $parts = explode('.', $basename);
        
        if (count($parts) <= 1) {
            return null;
        }
        
        return end($parts);
    }

    /**
     * Check if the URL has a specific extension.
     *
     * @param string $extension
     * @return bool
     */
    public function hasExtension(string $extension): bool
    {
        $currentExt = $this->extension();
        return $currentExt !== null && strtolower($currentExt) === strtolower($extension);
    }

    /**
     * Check if the domain is a specific domain.
     *
     * @param string $domain
     * @return bool
     */
    public function isDomain(string $domain): bool
    {
        $host = $this->host();
        return $host !== null && strtolower($host) === strtolower($domain);
    }

    /**
     * Check if the domain is a subdomain of a specific domain.
     *
     * @param string $domain
     * @return bool
     */
    public function isSubdomainOf(string $domain): bool
    {
        $host = $this->host();
        
        if (empty($host)) {
            return false;
        }
        
        return substr(strtolower($host), -strlen($domain) - 1) === '.' . strtolower($domain);
    }
} 