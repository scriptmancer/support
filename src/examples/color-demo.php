<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Scriptmancer\Support\Color;

echo "=== Color Utility Examples ===\n";

// Hex expansion
$shortHex = '#3aF';
echo "Expand #RGB: $shortHex => " . Color::expandHex($shortHex) . "\n";

// toRgbString, toRgbaString
$hex = '#3498db';
echo "toRgbString: " . Color::toRgbString($hex) . "\n";
echo "toRgbaString: " . Color::toRgbaString($hex) . "\n";

// toHslString, toHslaString
$rgb = 'rgb(52,152,219)';
echo "toHslString: " . Color::toHslString($rgb) . "\n";
echo "toHslaString: " . Color::toHslaString($rgb) . "\n";

// Lighten / Darken
$light = Color::lighten($hex, 0.2);
echo "Lighten 20%: $light\n";
$dark = Color::darken($hex, 0.2);
echo "Darken 20%: $dark\n";

// Invert
$inv = Color::invert($hex);
echo "Invert: $inv\n";

// Blend
$blend = Color::blend('#3498db', '#e74c3c', 0.5);
echo "Blend #3498db/#e74c3c 50-50: $blend\n";

// Contrast color
$bg = '#222';
echo "Best contrast for $bg: " . Color::getContrastColor($bg) . "\n";

// Random color
$rand = Color::random();
echo "Random color: $rand\n";

// toHex, toRgb, toHsl
$rgbStr = 'rgb(52,152,219)';
echo "toHex: " . Color::toHex($rgbStr) . "\n";
echo "toRgb: "; print_r(Color::toRgb($hex));
echo "toHsl: "; print_r(Color::toHsl($hex));

echo "\n=== Test Complete ===\n";
