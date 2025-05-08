<?php

namespace Nazim\Support;

class Html
{
    /**
     * Strip HTML tags from a string.
     *
     * @param string $string
     * @param string|array|null $allowableTags
     * @return string
     */
    public static function strip(string $string, string|array|null $allowableTags = null): string
    {
        return strip_tags($string, $allowableTags);
    }

    /**
     * Convert special characters to HTML entities.
     *
     * @param string $string
     * @param int $flags
     * @param string $encoding
     * @param bool $doubleEncode
     * @return string
     */
    public static function entities(
        string $string,
        int $flags = ENT_QUOTES | ENT_HTML5,
        string $encoding = 'UTF-8',
        bool $doubleEncode = true
    ): string {
        return htmlentities($string, $flags, $encoding, $doubleEncode);
    }

    /**
     * Convert special HTML entities back to characters.
     *
     * @param string $string
     * @param int $flags
     * @return string
     */
    public static function decode(string $string, int $flags = ENT_QUOTES | ENT_HTML5): string
    {
        return html_entity_decode($string, $flags);
    }

    /**
     * Convert HTML special characters.
     *
     * @param string $string
     * @param int $flags
     * @param string $encoding
     * @param bool $doubleEncode
     * @return string
     */
    public static function special(
        string $string,
        int $flags = ENT_QUOTES | ENT_HTML5,
        string $encoding = 'UTF-8',
        bool $doubleEncode = true
    ): string {
        return htmlspecialchars($string, $flags, $encoding, $doubleEncode);
    }

    /**
     * Create an HTML attribute string from an array.
     *
     * @param array $attributes
     * @return string
     */
    public static function attributes(array $attributes): string
    {
        $html = '';

        foreach ($attributes as $key => $value) {
            // For numeric keys, the value is the attribute name
            if (is_numeric($key)) {
                $html .= ' ' . $value;
            } 
            // For boolean true, just add the attribute name
            elseif (is_bool($value) && $value) {
                $html .= ' ' . $key;
            } 
            // For other non-null values, add attribute="value"
            elseif (!is_null($value) && !is_bool($value)) {
                $html .= ' ' . $key . '="' . static::special($value) . '"';
            }
        }

        return $html;
    }

    /**
     * Create an HTML tag.
     *
     * @param string $name
     * @param array $attributes
     * @param string|null $content
     * @return string
     */
    public static function tag(string $name, array $attributes = [], ?string $content = null): string
    {
        $attributeString = static::attributes($attributes);

        if (is_null($content)) {
            return '<' . $name . $attributeString . '>';
        }

        return '<' . $name . $attributeString . '>' . $content . '</' . $name . '>';
    }

    /**
     * Create a self-closing HTML tag.
     *
     * @param string $name
     * @param array $attributes
     * @return string
     */
    public static function selfClosingTag(string $name, array $attributes = []): string
    {
        $attributeString = static::attributes($attributes);
        return '<' . $name . $attributeString . ' />';
    }

    /**
     * Create an HTML link.
     *
     * @param string $url
     * @param string $content
     * @param array $attributes
     * @return string
     */
    public static function link(string $url, string $content, array $attributes = []): string
    {
        $attributes['href'] = $url;
        return static::tag('a', $attributes, $content);
    }

    /**
     * Create an HTML image.
     *
     * @param string $src
     * @param string|null $alt
     * @param array $attributes
     * @return string
     */
    public static function image(string $src, ?string $alt = null, array $attributes = []): string
    {
        $attributes['src'] = $src;
        
        if (!is_null($alt)) {
            $attributes['alt'] = $alt;
        }
        
        return static::selfClosingTag('img', $attributes);
    }

    /**
     * Extract all links from an HTML string.
     *
     * @param string $html
     * @return array
     */
    public static function extractLinks(string $html): array
    {
        preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $html, $matches);
        return $matches['href'] ?? [];
    }

    /**
     * Extract all images from an HTML string.
     *
     * @param string $html
     * @return array
     */
    public static function extractImages(string $html): array
    {
        preg_match_all('/<img[^>]+src=([\'"])(?<src>.+?)\1[^>]*>/i', $html, $matches);
        return $matches['src'] ?? [];
    }

    /**
     * Extract meta tags from an HTML string.
     *
     * @param string $html
     * @return array
     */
    public static function extractMetaTags(string $html): array
    {
        $result = [];
        preg_match_all('/<meta[^>]+>/i', $html, $matches);
        
        foreach ($matches[0] as $meta) {
            if (preg_match('/name=([\'"])(?<name>.+?)\1/i', $meta, $nameMatch)) {
                $name = $nameMatch['name'];
                
                if (preg_match('/content=([\'"])(?<content>.+?)\1/i', $meta, $contentMatch)) {
                    $result[$name] = $contentMatch['content'];
                }
            }
        }
        
        return $result;
    }

    /**
     * Clean HTML content using a whitelist of allowed tags.
     *
     * @param string $html
     * @param array $allowedTags
     * @return string
     */
    public static function clean(string $html, array $allowedTags = ['p', 'br', 'strong', 'em', 'u', 'a', 'ul', 'ol', 'li']): string
    {
        // Convert allowed tags array to a string format for strip_tags
        $allowedTagsString = '';
        foreach ($allowedTags as $tag) {
            $allowedTagsString .= "<{$tag}>";
        }
        
        return strip_tags($html, $allowedTagsString);
    }

    /**
     * Minify HTML.
     *
     * @param string $html
     * @return string
     */
    public static function minify(string $html): string
    {
        // Remove comments
        $html = preg_replace('/<!--(?!<!)[^\[>].*?-->/s', '', $html);
        
        // Remove whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Remove whitespace between tags
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Trim whitespace
        return trim($html);
    }

    /**
     * Convert plain text URLs to HTML links.
     *
     * @param string $text
     * @param array $attributes
     * @return string
     */
    public static function linkify(string $text, array $attributes = []): string
    {
        $pattern = '/(https?:\/\/[^\s]+)/i';
        
        return preg_replace_callback($pattern, function($matches) use ($attributes) {
            $url = $matches[0];
            return static::link($url, $url, $attributes);
        }, $text);
    }

    /**
     * Convert line breaks to <br> tags.
     *
     * @param string $text
     * @return string
     */
    public static function nl2br(string $text): string
    {
        return nl2br($text);
    }
} 