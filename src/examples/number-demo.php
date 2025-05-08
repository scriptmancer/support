<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Nazim\Support\Number;

// Different ways to create Number objects
echo "=== Creating Number Objects ===\n";
$turkish = new Number('tr_TR');
echo "Using constructor (tr_TR): " . $turkish->format(1234.56) . "\n";

$english = Number::locale('en_US');
echo "Using static method (en_US): " . $english->format(1234.56) . "\n";

$german = number('de_DE');
echo "Using helper function (de_DE): " . $german->format(1234.56) . "\n";

// Basic number formatting
echo "\n=== Basic Number Formatting ===\n";
$num = number();
echo "Original number: 1234567.89\n";
echo "Format (default 2 decimals): " . $num->format(1234567.89) . "\n";
echo "Format (0 decimals): " . $num->format(1234567.89, 0) . "\n";
echo "Format (3 decimals): " . $num->format(1234567.89, 3) . "\n";

// Currency formatting
echo "\n=== Currency Formatting ===\n";
echo "Turkish (TRY): " . $turkish->currency(1234.56) . "\n";
echo "Turkish (USD): " . $turkish->currency(1234.56, 'USD') . "\n";
echo "English (USD): " . $english->currency(1234.56) . "\n";
echo "English (EUR): " . $english->currency(1234.56, 'EUR') . "\n";
echo "German (EUR): " . $german->currency(1234.56) . "\n";
echo "German (GBP): " . $german->currency(1234.56, 'GBP') . "\n";

// Money format (legacy)
echo "\n=== Money Format (Legacy) ===\n";
echo "Turkish: " . $turkish->money(1234.56) . "\n";

// Percentage
echo "\n=== Percentage Formatting ===\n";
echo "0.75 as Turkish percentage: " . $turkish->percent(0.75) . "\n";
echo "0.75 as English percentage: " . $english->percent(0.75) . "\n";
echo "0.75 as German percentage: " . $german->percent(0.75) . "\n";

// Short form
echo "\n=== Short Form Formatting ===\n";
echo "1500 in short form: " . $turkish->shortForm(1500) . "\n";
echo "1500000 in short form: " . $turkish->shortForm(1500000) . "\n";
echo "1500000000 in short form: " . $turkish->shortForm(1500000000) . "\n";

// Spell out
echo "\n=== Spelling Out Numbers ===\n";
echo "42 spelled out in Turkish: " . $turkish->spellOut(15002356100) . "\n";
echo "42 spelled out in English: " . $english->spellOut(42) . "\n";
echo "42 spelled out in German: " . $german->spellOut(42) . "\n";

// Ordinal numbers
echo "\n=== Ordinal Numbers ===\n";
$numbers = [1, 2, 3, 4, 11, 21, 22, 23];
echo "Turkish ordinals: ";
foreach ($numbers as $n) {
    echo $turkish->ordinal($n) . " ";
}
echo "\n";

echo "English ordinals: ";
foreach ($numbers as $n) {
    echo $english->ordinal($n) . " ";
}
echo "\n";

echo "German ordinals: ";
foreach ($numbers as $n) {
    echo $german->ordinal($n) . " ";
}
echo "\n";

// Roman numerals
echo "\n=== Roman Numerals ===\n";
for ($i = 1; $i <= 10; $i++) {
    echo $i . " in Roman numerals: " . $num->roman($i) . "\n";
}
echo "1984 in Roman numerals: " . $num->roman(1984) . "\n";

// Static methods
echo "\n=== Static Methods ===\n";
echo "Is 42 even? " . (Number::isEven(42) ? "Yes" : "No") . "\n";
echo "Is 42 odd? " . (Number::isOdd(42) ? "Yes" : "No") . "\n";
echo "Is 43 even? " . (Number::isEven(43) ? "Yes" : "No") . "\n";
echo "Is 43 odd? " . (Number::isOdd(43) ? "Yes" : "No") . "\n";

// Human readable file sizes
echo "\n=== Human Readable File Sizes ===\n";
echo "512 bytes: " . $turkish->humanReadable(512) . "\n";
echo "2048 bytes: " . $turkish->humanReadable(2048) . "\n";
echo "1500000 bytes: " . $turkish->humanReadable(1500000) . "\n";
echo "1073741824 bytes (1GB): " . $turkish->humanReadable(1073741824) . "\n";
echo "1099511627776 bytes (1TB): " . $turkish->humanReadable(1099511627776) . "\n";
echo "Same size with English locale: " . $english->humanReadable(1099511627776) . "\n";
echo "Same size with German locale: " . $german->humanReadable(1099511627776) . "\n";
echo "With 0 decimal places: " . $turkish->humanReadable(1500000, 0) . "\n";
echo "With 3 decimal places: " . $turkish->humanReadable(1500000, 3) . "\n";
 
// Done
echo "\n=== Test Complete ===\n"; 