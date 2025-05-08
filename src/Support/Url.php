<?php

namespace Nazim\Support;

class Url
{
    /**
     * Parse a URL and return its components.
     *
     * @param string $url
     * @return array|false
     */
    public static function parse(string $url): array|false
    {
        return parse_url($url);
    }

    /**
     * Build a URL from its components.
     *
     * @param array $parts
     * @return string
     */
    public static function build(array $parts): string
    {
        $url = '';
        
        if (!empty($parts['scheme'])) {
            $url .= $parts['scheme'] . '://';
        }
        
        if (!empty($parts['user'])) {
            $url .= $parts['user'];
            if (!empty($parts['pass'])) {
                $url .= ':' . $parts['pass'];
            }
            $url .= '@';
        }
        
        if (!empty($parts['host'])) {
            $url .= $parts['host'];
        }
        
        if (!empty($parts['port'])) {
            $url .= ':' . $parts['port'];
        }
        
        if (!empty($parts['path'])) {
            $url .= $parts['path'];
        }
        
        if (!empty($parts['query'])) {
            $url .= '?' . $parts['query'];
        }
        
        if (!empty($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }
        
        return $url;
    }

    /**
     * Validate if the given string is a valid URL.
     *
     * @param string $url
     * @return bool
     */
    public static function isValid(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Get the scheme from a URL.
     *
     * @param string $url
     * @return string|null
     */
    public static function scheme(string $url): ?string
    {
        $parts = static::parse($url);
        return $parts['scheme'] ?? null;
    }

    /**
     * Get the host from a URL.
     *
     * @param string $url
     * @return string|null
     */
    public static function host(string $url): ?string
    {
        $parts = static::parse($url);
        return $parts['host'] ?? null;
    }

    /**
     * Get the port from a URL.
     *
     * @param string $url
     * @return int|null
     */
    public static function port(string $url): ?int
    {
        $parts = static::parse($url);
        return $parts['port'] ?? null;
    }

    /**
     * Get the path from a URL.
     *
     * @param string $url
     * @return string|null
     */
    public static function path(string $url): ?string
    {
        $parts = static::parse($url);
        return $parts['path'] ?? null;
    }

    /**
     * Get the query string from a URL.
     *
     * @param string $url
     * @return string|null
     */
    public static function query(string $url): ?string
    {
        $parts = static::parse($url);
        return $parts['query'] ?? null;
    }

    /**
     * Get the fragment from a URL.
     *
     * @param string $url
     * @return string|null
     */
    public static function fragment(string $url): ?string
    {
        $parts = static::parse($url);
        return $parts['fragment'] ?? null;
    }

    /**
     * Parse the query string from a URL into an array.
     *
     * @param string $url
     * @return array
     */
    public static function parseQuery(string $url): array
    {
        $query = static::query($url);
        
        if (empty($query)) {
            return [];
        }
        
        $result = [];
        parse_str($query, $result);
        
        return $result;
    }

    /**
     * Build a query string from an array.
     *
     * @param array $params
     * @return string
     */
    public static function buildQuery(array $params): string
    {
        return http_build_query($params);
    }

    /**
     * Add a query parameter to a URL.
     *
     * @param string $url
     * @param string $key
     * @param string $value
     * @return string
     */
    public static function addQuery(string $url, string $key, string $value): string
    {
        $parts = static::parse($url);
        $query = [];
        
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
        }
        
        $query[$key] = $value;
        $parts['query'] = static::buildQuery($query);
        
        return static::build($parts);
    }

    /**
     * Remove a query parameter from a URL.
     *
     * @param string $url
     * @param string $key
     * @return string
     */
    public static function removeQuery(string $url, string $key): string
    {
        $parts = static::parse($url);
        $query = [];
        
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
            if (isset($query[$key])) {
                unset($query[$key]);
            }
        }
        
        $parts['query'] = empty($query) ? '' : static::buildQuery($query);
        
        return static::build($parts);
    }

    /**
     * Determine if a URL is using HTTPS.
     *
     * @param string $url
     * @return bool
     */
    public static function isHttps(string $url): bool
    {
        return static::scheme($url) === 'https';
    }

    /**
     * Convert a URL to HTTPS.
     *
     * @param string $url
     * @return string
     */
    public static function toHttps(string $url): string
    {
        $parts = static::parse($url);
        
        if (isset($parts['scheme'])) {
            $parts['scheme'] = 'https';
        }
        
        return static::build($parts);
    }

    /**
     * Convert a URL to HTTP.
     *
     * @param string $url
     * @return string
     */
    public static function toHttp(string $url): string
    {
        $parts = static::parse($url);
        
        if (isset($parts['scheme'])) {
            $parts['scheme'] = 'http';
        }
        
        return static::build($parts);
    }

    /**
     * Get the domain from a URL.
     *
     * @param string $url
     * @param bool $withSubdomain
     * @return string|null
     */
    public static function domain(string $url, bool $withSubdomain = true): ?string
    {
        $host = static::host($url);
        
        if (empty($host)) {
            return null;
        }
        
        if ($withSubdomain) {
            return $host;
        }
        
        $hostParts = explode('.', $host);
        
        // Handle cases like example.com, sub.example.com
        if (count($hostParts) > 1) {
            $topLevelCount = 2; // For domains like example.com
            
            // Handle special cases for country-specific domains
            $lastPart = end($hostParts);
            $secondLastPart = prev($hostParts);
            
            // Check for country code TLDs with subdomains like .co.uk, .com.br
            if (strlen($lastPart) === 2 && strlen($secondLastPart) <= 3) {
                $topLevelCount = 3;
            }
            
            // Get only the main domain parts
            $mainParts = array_slice($hostParts, -$topLevelCount, $topLevelCount);
            return implode('.', $mainParts);
        }
        
        return $host;
    }

    /**
     * Get the subdomain from a URL.
     *
     * @param string $url
     * @return string|null
     */
    public static function subdomain(string $url): ?string
    {
        $host = static::host($url);
        $domain = static::domain($url, false);
        
        if (empty($host) || empty($domain) || $host === $domain) {
            return null;
        }
        
        return rtrim(substr($host, 0, -strlen($domain) - 1), '.');
    }

    /**
     * Join URL path segments.
     *
     * @param string ...$segments
     * @return string
     */
    public static function join(string ...$segments): string
    {
        $path = '';
        
        foreach ($segments as $segment) {
            $segment = trim($segment, '/');
            
            if (empty($path)) {
                $path = $segment;
            } else {
                $path .= '/' . $segment;
            }
        }
        
        return $path;
    }

    /**
     * Get the current URL.
     *
     * @return string
     */
    public static function current(): string
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        
        return $protocol . '://' . $host . $uri;
    }

    /**
     * Extract URL from a string.
     *
     * @param string $text
     * @return array
     */
    public static function extractUrls(string $text): array
    {
        $pattern = '/\b(?:https?:\/\/|www\.)[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/))/i';
        preg_match_all($pattern, $text, $matches);
        
        return $matches[0] ?? [];
    }
} 