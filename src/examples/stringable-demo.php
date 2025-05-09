<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Scriptmancer\Support\Stringable;

// Different ways to create Stringable instances
echo "=== Creating Stringable Objects ===\n";
$string1 = new Stringable('Hello, World!');
echo "Using constructor: " . $string1 . "\n";

$string2 = str('Hello, Universe!');
echo "Using helper function: " . $string2 . "\n";

$string3 = Stringable::of('Hello, Galaxy!');
echo "Using static method: " . $string3 . "\n";

// Basic string operations
echo "\n=== Basic String Operations ===\n";
$text = str('  Hello, World!  ');
echo "Original: '" . $text . "'\n";
echo "Trimmed: '" . $text->trim() . "'\n";
echo "Upper: '" . $text->upper() . "'\n";
echo "Lower: '" . $text->lower() . "'\n";
echo "Length: " . $text->length() . "\n";
echo "Word Count: " . $text->wordCount() . "\n";

// String checks
echo "\n=== String Checks ===\n";
$check = str('Hello, World!');
echo "String: '" . $check . "'\n";
echo "Contains 'World': " . ($check->contains('World') ? 'true' : 'false') . "\n";
echo "Starts with 'Hello': " . ($check->startsWith('Hello') ? 'true' : 'false') . "\n";
echo "Ends with '!': " . ($check->endsWith('!') ? 'true' : 'false') . "\n";
echo "Is Empty: " . ($check->isEmpty() ? 'true' : 'false') . "\n";
echo "Is Not Empty: " . ($check->isNotEmpty() ? 'true' : 'false') . "\n";

// String transformations
echo "\n=== String Transformations ===\n";
$transform = str('hello_world');
echo "Original: '" . $transform . "'\n";
echo "Camel Case: '" . $transform->camel() . "'\n";
echo "Snake Case: '" . $transform->snake() . "'\n";
echo "Kebab Case: '" . $transform->kebab() . "'\n";
echo "Title Case: '" . $transform->title() . "'\n";

// Method chaining
echo "\n=== Method Chaining ===\n";
$chained = str('  HELLO, WORLD!  ')
    ->trim()
    ->lower()
    ->replace('world', 'PHP')
    ->title();
echo "After Chaining: '" . $chained . "'\n";

// Substring operations
echo "\n=== Substring Operations ===\n";
$longText = str('The quick brown fox jumps over the lazy dog');
echo "Original: '" . $longText . "'\n";
echo "Substring (4, 5): '" . $longText->substr(4, 5) . "'\n";
echo "Limit to 15 chars: '" . $longText->limit(15) . "'\n";

// Dynamic method calls using __call
echo "\n=== Dynamic Method Calls ===\n";
$dynamic = str('This is a test string for *pattern* matching');
echo "Original: '" . $dynamic . "'\n";
echo "Matches pattern '*pattern*': " . ($dynamic->is('*pattern*') ? 'true' : 'false') . "\n";
echo "Replace First 'is' with 'was': '" . $dynamic->replaceFirst('is', 'was') . "'\n";
echo "Replace Last 'ing' with 'ed': '" . $dynamic->replaceLast('ing', 'ed') . "'\n";

// Characters
echo "\n=== Characters ===\n";
$chars = str('abc123');
echo "Characters: " . print_r($chars->chars(), true) . "\n";

// Done
echo "\n=== Test Complete ===\n"; 