<?php

namespace Scriptmancer\Support;

class Color
{
    /**
     * Convert a color to hex format without the # symbol.
     *
     * @param string $color The color in hex format (with or without #)
     * @return string The hex color without # symbol
     */
    /**
     * Expand short hex color (#RGB or #RGBA) to full hex (#RRGGBB or #RRGGBBAA).
     * @param string $color
     * @return string
     */
    public static function expandHex(string $color): string
    {
        $hex = ltrim($color, '#');
        if (strlen($hex) === 3) {
            return $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        if (strlen($hex) === 4) {
            return $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2].$hex[3].$hex[3];
        }
        return $hex;
    }

    public static function toHex(string $color): string
    {
        return self::expandHex($color);
    }

    /**
     * Convert a hex color to RGB components.
     *
     * @param string $color The color in hex format (e.g., #RRGGBB)
     * @return array<int, int> An array containing [red, green, blue] components (0-255)
     */
    public static function toRgb(string $color): array
    {
        $hex = self::expandHex($color);
        $rgb = sscanf($hex, '%02x%02x%02x');
        return $rgb !== null ? $rgb : [0, 0, 0];
    }

    /**
     * Output as CSS rgb(r,g,b) string.
     * @param string $color
     * @return string
     */
    public static function toRgbString(string $color): string
    {
        [$r, $g, $b] = self::toRgb($color);
        return "rgb($r, $g, $b)";
    }

    /**
     * Convert a hex color to RGBA components.
     *
     * @param string $color The color in hex format with alpha (e.g., #RRGGBBAA)
     * @return array<int, int> An array containing [red, green, blue, alpha] components (0-255)
     */
    public static function toRgba(string $color): array
    {
        $hex = self::expandHex($color);
        $rgba = sscanf($hex, '%02x%02x%02x%02x');
        if ($rgba === null) {
            $rgb = sscanf($hex, '%02x%02x%02x');
            return $rgb !== null ? array_merge($rgb, [255]) : [0, 0, 0, 255];
        }
        return $rgba;
    }

    /**
     * Output as CSS rgba(r,g,b,a) string.
     * @param string $color
     * @return string
     */
    public static function toRgbaString(string $color): string
    {
        [$r, $g, $b, $a] = self::toRgba($color);
        $alpha = round($a / 255, 3);
        return "rgba($r, $g, $b, $alpha)";
    }

    /**
     * Convert a hex color to HSL components.
     *
     * @param string $color The color in hex format (e.g., #RRGGBB)
     * @return array<int, float> An array containing [hue, saturation, lightness] components
     */
    public static function toHsl(string $color): array
    {
        [$r, $g, $b] = array_map(fn ($value) => $value / 255, self::toRgb($color));
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;
        if ($max === $min) {
            $h = $s = 0;
        } else {
            $diff = $max - $min;
            $s = $l > 0.5 ? $diff / (2 - $max - $min) : $diff / ($max + $min);
            $h = match(true) {
                $max === $r => ($g - $b) / $diff + ($g < $b ? 6 : 0),
                $max === $g => ($b - $r) / $diff + 2,
                default => ($r - $g) / $diff + 4,
            };
            $h = round($h * 60);
        }
        return [$h, $s * 100, $l * 100];
    }

    /**
     * Output as CSS hsl(h,s%,l%) string.
     * @param string $color
     * @return string
     */
    public static function toHslString(string $color): string
    {
        [$h, $s, $l] = self::toHsl($color);
        return "hsl($h, $s%, $l%)";
    }

    /**
     * Convert a hex color to HSLA components.
     *
     * @param string $color The color in hex format with alpha (e.g., #RRGGBBAA)
     * @return array<int, float> An array containing [hue, saturation, lightness, alpha] components
     */
    public static function toHsla(string $color): array
    {
        $rgba = self::toRgba($color);
        $hsl = self::toHsl($color);
        // Add alpha component normalized to 0-1
        $hsl[] = isset($rgba[3]) ? $rgba[3] / 255 : 1.0;
        return $hsl;
    }

    /**
     * Output as CSS hsla(h,s%,l%,a) string.
     * @param string $color
     * @return string
     */
    public static function toHslaString(string $color): string
    {
        [$h, $s, $l, $a] = self::toHsla($color);
        $alpha = round($a, 3);
        return "hsla($h, $s%, $l%, $alpha)";
    }
    
    /**
     * Generate a random color in hex format.
     *
     * @param bool $withHash Whether to include the # symbol
     * @return string Random color in hex format
     */
    public static function random(bool $withHash = true): string
    {
        $color = sprintf('%06x', mt_rand(0, 0xFFFFFF));
        return $withHash ? "#{$color}" : $color;
    }

    /**
     * Lighten a color by a percentage (0-100).
     * @param string $color
     * @param float $amount
     * @return string
     */
    public static function lighten(string $color, float $amount): string
    {
        [$h, $s, $l] = self::toHsl($color);
        $l = min(100, $l + $amount);
        return self::fromHsl($h, $s, $l);
    }

    /**
     * Darken a color by a percentage (0-100).
     * @param string $color
     * @param float $amount
     * @return string
     */
    public static function darken(string $color, float $amount): string
    {
        [$h, $s, $l] = self::toHsl($color);
        $l = max(0, $l - $amount);
        return self::fromHsl($h, $s, $l);
    }

    /**
     * Invert a color.
     * @param string $color
     * @return string
     */
    public static function invert(string $color): string
    {
        [$r, $g, $b] = self::toRgb($color);
        return sprintf("#%02x%02x%02x", 255 - $r, 255 - $g, 255 - $b);
    }

    /**
     * Blend two colors by a ratio (0-1).
     * @param string $color1
     * @param string $color2
     * @param float $ratio
     * @return string
     */
    public static function blend(string $color1, string $color2, float $ratio): string
    {
        [$r1, $g1, $b1] = self::toRgb($color1);
        [$r2, $g2, $b2] = self::toRgb($color2);
        $r = round($r1 * (1 - $ratio) + $r2 * $ratio);
        $g = round($g1 * (1 - $ratio) + $g2 * $ratio);
        $b = round($b1 * (1 - $ratio) + $b2 * $ratio);
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

    /**
     * Get best contrast color (black or white) for a given background color.
     * @param string $color
     * @return string
     */
    public static function getContrastColor(string $color): string
    {
        [$r, $g, $b] = self::toRgb($color);
        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        return $luminance > 0.5 ? '#000000' : '#FFFFFF';
    }

    /**
     * Create hex from HSL values.
     * @param float $h
     * @param float $s
     * @param float $l
     * @return string
     */
    public static function fromHsl(float $h, float $s, float $l): string
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;
        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = self::hueToRgb($p, $q, $h + 1/3);
            $g = self::hueToRgb($p, $q, $h);
            $b = self::hueToRgb($p, $q, $h - 1/3);
        }
        return sprintf("#%02x%02x%02x", round($r*255), round($g*255), round($b*255));
    }

    protected static function hueToRgb($p, $q, $t) {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
        return $p;
    }
}