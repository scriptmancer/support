<?php

namespace Nazim\Support;

class Color
{
    /**
     * Convert a color to hex format without the # symbol.
     *
     * @param string $color The color in hex format (with or without #)
     * @return string The hex color without # symbol
     */
    public static function toHex(string $color): string
    {
        return str_replace('#', '', $color);
    }

    /**
     * Convert a hex color to RGB components.
     *
     * @param string $color The color in hex format (e.g., #RRGGBB)
     * @return array<int, int> An array containing [red, green, blue] components (0-255)
     */
    public static function toRgb(string $color): array
    {
        $rgb = sscanf(self::toHex($color), '%02x%02x%02x');
        return $rgb !== null ? $rgb : [0, 0, 0];
    }

    /**
     * Convert a hex color to RGBA components.
     *
     * @param string $color The color in hex format with alpha (e.g., #RRGGBBAA)
     * @return array<int, int> An array containing [red, green, blue, alpha] components (0-255)
     */
    public static function toRgba(string $color): array
    {
        $rgba = sscanf(self::toHex($color), '%02x%02x%02x%02x');
        return $rgba !== null ? $rgba : [0, 0, 0, 255];
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
}