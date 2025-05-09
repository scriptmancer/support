# Nazim Support

Support package for the Nazim framework.

## Features

- Debug helpers using Kint
- Collection implementation similar to [Tighten Collect](https://github.com/tighten/collect)
- Immutable Date and DateObject helpers
- Fluent string manipulation with Stringable
- Comprehensive array manipulation utilities
- String helper functions
- Locale-specific string sorting with Collator integration
- Locale-aware number formatting with NumberFormatter
- Advanced collection debugging with CollectionDebugger
- Secure password hashing and cryptography with Hash
- OpenSSL encryption/decryption utilities
- Pipeline pattern for chaining operations

## Installation

```bash
composer require scriptmancer/support
```

## Usage

### Debug Helpers

This package provides debugging functionality powered by Kint:

```php
// Dump a variable and continue execution
d($var);

// Dump a variable and die
dd($var);
```

### Collections

Work with arrays in an object-oriented way using the rich Collection class:

```php
// Create a collection
$collection = collect([1, 2, 3, 4, 5]);

// Basic operations
$count = $collection->count(); // 5
$first = $collection->first(); // 1
$last = $collection->last(); // 5
$isEmpty = $collection->isEmpty(); // false

// Map and transform items
$doubled = $collection->map(function ($item) {
    return $item * 2;
}); // [2, 4, 6, 8, 10]

// Filter items
$even = $collection->filter(function ($item) {
    return $item % 2 === 0;
}); // [2 => 2, 4 => 4]

// Filtering by key-value pairs
$admins = $users->where('role', 'admin'); // Only users with role=admin

// Pluck values from arrays or objects
$users = collect([
    ['name' => 'John', 'age' => 30],
    ['name' => 'Jane', 'age' => 25]
]);
$names = $users->pluck('name'); // ['John', 'Jane']

// Sorting
$sorted = collect([3, 1, 5, 2, 4])->sort(); // [1, 2, 3, 4, 5]
$sortedByAge = $users->sortBy('age'); // Jane first, then John

// Aggregation methods
$sum = $collection->sum(); // 15
$avg = $collection->avg(); // 3
$min = $collection->min(); // 1
$max = $collection->max(); // 5
$median = $collection->median(); // 3
$mode = $collection->mode(); // [3] (most frequent value)

// Collection manipulation
$collection->push(6); // Add to end
$collection->prepend(0); // Add to beginning 
$first = $collection->shift(); // Remove and return first item
$last = $collection->pop(); // Remove and return last item

// Chunking
$chunks = $collection->chunk(2); // [[1,2], [3,4], [5]]

// Unique values
$unique = collect([1, 1, 2, 2, 3])->unique(); // [1, 2, 3]

// Grouping
$grouped = $collection->groupBy(function ($item) {
    return $item % 2 === 0 ? 'even' : 'odd';
}); // ['odd' => [1, 3, 5], 'even' => [2, 4]]

// Take specific number of items
$firstThree = $collection->take(3); // [1, 2, 3]
$lastTwo = $collection->take(-2); // [4, 5]

// Merge collections
$merged = $collection->merge([6, 7, 8]); // [1, 2, 3, 4, 5, 6, 7, 8]

// Only or except specific keys
$only = collect(['a' => 1, 'b' => 2, 'c' => 3])->only(['a', 'c']); // ['a' => 1, 'c' => 3]
$except = collect(['a' => 1, 'b' => 2, 'c' => 3])->except(['b']); // ['a' => 1, 'c' => 3]

// Advanced operations
$times = Collection::times(4); // [1, 2, 3, 4]
$times = Collection::times(4, function($n) { return $n * 2; }); // [2, 4, 6, 8] 

$partitioned = $collection->partition(function ($i) { return $i > 3; }); // [[4, 5], [1, 2, 3]]
$random = $collection->random(); // Random item from collection
$random = $collection->random(2); // Collection with 2 random items

$crossJoin = collect([1, 2])->crossJoin(['a', 'b']); // [[1,'a'], [1,'b'], [2,'a'], [2,'b']]
$padded = collect([1, 2])->pad(5, 0); // [1, 2, 0, 0, 0]
$dotted = collect(['foo' => ['bar' => 'baz']])->dot(); // ['foo.bar' => 'baz']
$flipped = collect(['name' => 'taylor'])->flip(); // ['taylor' => 'name']
$page = $collection->forPage(2, 2); // [3, 4] (items for page 2, 2 per page)

// Chaining methods
$result = $collection
    ->filter(function ($item) { return $item > 2; })
    ->map(function ($item) { return $item * 10; })
    ->take(2); // [30, 40]
```

### Collection Debugging

The Collection class includes a powerful debugging toolset:

```php
// Get a debugger instance
$debugger = $collection->debug();

// Or use the helper function
$debugger = collection_debug([1, 2, 3]);

// Dump collection and continue
$collection->debug()->dump();

// Dump as JSON
$collection->debug()->dumpJson();

// Print as a formatted table
$collection->debug()->table();

// Count value occurrences
$collection->debug()->countValues(); // For simple collections
$users->debug()->countValues('role'); // For associative collections

// Get statistics
$numbers->debug()->stats(); // Count, sum, avg, min, max, median, etc.

// Chain with other collection methods
$users
    ->where('role', 'admin')
    ->debug()->dump() // Dump admins
    ->pluck('name')
    ->debug()->table(); // Display admin names in a table
```

Run the included examples to see these features in action:

```bash
php run-collection-example.php
php run-collection-debug-example.php
php run-array-example.php
php run-color-example.php
```

### Hash & Cryptography

Secure password hashing and cryptographic functionality:

```php
use Scriptmancer\Support\Hash;

// Hash a password
$hashed = Hash::make('my-password');

// Verify a password
$isValid = Hash::check('my-password', $hashed);

// Check if a hash needs rehashing
$needsRehash = Hash::needsRehash($hashed);

// Adjust the default cost factor
Hash::setRounds(12); // Higher cost = more secure but slower

// Generate random strings
$random = Hash::random(16); // 16 character random string
$token = Hash::token(40); // 40 character secure token

// Create HMAC
$hmac = Hash::hmac('message', 'key', 'sha256');

// Hash with different algorithms
$sha256 = Hash::digest('message', 'sha256');
$md5 = Hash::digest('message', 'md5');

// Generate UUID (version 4)
$uuid = Hash::uuid(); // e.g. 550e8400-e29b-41d4-a716-446655440000

// Use the helper function
$hashed = hash_value('password');
```

Run the hash example:

```bash
php run-hash-example.php
```

### Advanced Cryptography with OpenSSL

In addition to basic hashing, the Hash class also provides OpenSSL encryption capabilities:

```php
use Scriptmancer\Support\Hash;

// Encrypt and decrypt data
$key = Hash::generateKey(); // Generate a secure key
$encrypted = Hash::encrypt('Secret message', $key);
$decrypted = Hash::decrypt($encrypted, $key);

// Get available ciphers
$ciphers = Hash::getAvailableCiphers();

// Use a custom cipher
$encrypted = Hash::encrypt('Secret message', $key, 'AES-128-CBC');

// Change the default cipher
Hash::setCipher('AES-128-CBC');

// Create a hash of a file
$fileHash = Hash::file('/path/to/file', 'sha256');
```

### Pipeline Pattern

The Pipeline pattern allows you to pass an object through a series of stages or transformations:

```php
use Scriptmancer\Support\Pipeline;

// Basic pipeline with closures
$result = Pipeline::make('Hello')
    ->through([
        function ($string, $next) {
            return $next($string . ', ');
        },
        function ($string, $next) {
            return $next($string . 'World');
        },
        function ($string, $next) {
            return $next($string . '!');
        },
    ])
    ->thenReturn();
// Result: "Hello, World!"

// Using pipe classes
class UppercasePipe
{
    public function handle($data, $next)
    {
        return $next(strtoupper($data));
    }
}

$result = Pipeline::make('hello')
    ->through([
        UppercasePipe::class,
        // Other pipes...
    ])
    ->thenReturn();

// Use the pipeline helper
$result = pipeline([1, 2, 3, 4, 5])
    ->through([
        function ($numbers, $next) {
            return $next(array_filter($numbers, fn($n) => $n % 2 === 0));
        },
        function ($numbers, $next) {
            return $next(array_map(fn($n) => $n * $n, $numbers));
        },
    ])
    ->thenReturn();

// Use a custom method name for pipe classes
$result = pipeline(10)
    ->through([MyPipe::class])
    ->via('process')  // Use 'process' method instead of default 'handle'
    ->thenReturn();
```

Run the pipeline example:

```bash
php run-pipeline-example.php
```

### Date Handling

Work with dates in an immutable way using DateObject:

```php
// Create a date object
$date = new \Scriptmancer\Support\DateObject();
// Or use the helper function
$date = date_obj();
$specificDate = date_obj('2023-01-15');

// Perform operations that return new instances
$tomorrow = $date->addDays(1);
$nextMonth = $date->addMonths(1);

// Check date conditions
$isWeekend = $date->isWeekend();
$isBetween = $date->isBetween($start, $end);

// Static creation methods
$today = \Scriptmancer\Support\DateObject::today();
$yesterday = \Scriptmancer\Support\DateObject::yesterday();
```

### String Manipulation

Fluent string manipulation with the Stringable class:

```php
// Create a string object
$string = new \Scriptmancer\Support\Stringable('Hello, World!');
// Or use the helper function
$string = str('Hello, World!');
// Or use the static method
$string = \Scriptmancer\Support\Stringable::of('Hello, World!');

// Method chaining
$result = str('  HELLO, WORLD!  ')
    ->trim()
    ->lower()
    ->replace('world', 'PHP')
    ->title();
// "Hello, PHP!"

// Transformations
$camelCase = str('hello_world')->camel(); // "helloWorld"
$snakeCase = str('helloWorld')->snake(); // "hello_world"
$kebabCase = str('helloWorld')->kebab(); // "hello-world"

// String checks
$contains = str('Hello, World!')->contains('World'); // true
$startsWith = str('Hello, World!')->startsWith('Hello'); // true
$endsWith = str('Hello, World!')->endsWith('!'); // true
```

### Array Utilities

A comprehensive set of array manipulation utilities:

```php
use Scriptmancer\Support\Arr;

// Getting and setting values
$value = Arr::get($array, 'key', 'default');
Arr::set($array, 'key', 'value');

// Array operations
$first = Arr::first($array);
$last = Arr::last($array);
$keys = Arr::keys($array);
$values = Arr::values($array);

// Functional operations
$mapped = Arr::map($array, fn($item) => $item * 2);
$filtered = Arr::filter($array, fn($item) => $item > 5);
$reduced = Arr::reduce($array, fn($carry, $item) => $carry + $item, 0);

// Array transformations
$unique = Arr::unique($array);
$sorted = Arr::sort($array);
$reversed = Arr::reverse($array);
$grouped = Arr::groupBy($array, 'key');
$wrapped = Arr::wrap('value'); // ['value'] - wraps non-arrays in an array

// Advanced operations
$contains = Arr::contains($array, 'value');
$every = Arr::every($array, fn($item) => $item > 0);
$some = Arr::some($array, fn($item) => $item > 10);

// Locale-specific string sorting
$turkish_words = ['şeker', 'armut', 'çilek', 'elma', 'üzüm', 'ıspanak'];
$sorted = Arr::string_sort($turkish_words, 'tr_TR'); // Sort using Turkish locale

// Sorting arrays of objects or associative arrays by field
$people = [
    ['name' => 'Şule', 'age' => 30],
    ['name' => 'Ahmet', 'age' => 25],
    ['name' => 'Çiğdem', 'age' => 28]
];
$sortedByName = Arr::string_sort($people, 'tr_TR', 'name');

// Sorting associative arrays by key
$months = [
    'Şubat' => 2,
    'Ocak' => 1,
    'Mart' => 3
];
$sortedMonths = Arr::string_sort_by_key($months, 'tr_TR');
```

### Color Utilities

Manipulate and convert colors with the `Color` utility class:

```php
use Scriptmancer\Support\Color;

// Expand short hex
Color::expandHex('#3aF'); // '33aaFF'

// Convert to RGB/RGBA strings
Color::toRgbString('#3498db'); // 'rgb(52, 152, 219)'
Color::toRgbaString('#3498db'); // 'rgba(52, 152, 219, 0)'

// Convert to HSL/HSLA strings
Color::toHslString('rgb(52,152,219)');
Color::toHslaString('rgb(52,152,219)');

// Lighten/Darken
Color::lighten('#3498db', 0.2); // Lighten 20%
Color::darken('#3498db', 0.2); // Darken 20%

// Invert
Color::invert('#3498db');

// Blend two colors
Color::blend('#3498db', '#e74c3c', 0.5);

// Get best contrast color (black/white)
Color::getContrastColor('#222'); // '#FFFFFF'

// Generate a random color
Color::random();

// Convert between formats
Color::toHex('rgb(52,152,219)');
Color::toRgb('#3498db');
Color::toHsl('#3498db');
```

Run the color example to see these features:

```bash
php run-color-example.php
```

### Number Formatting

Format numbers with proper locale-specific formatting:

```php
// Create a number formatter
$number = new \Scriptmancer\Support\Number('tr_TR');
// Or use the helper function
$number = number();
// Or specify a different locale
$english = number('en_US');
$german = number('de_DE');

// Basic number formatting
echo $number->format(1234.56); // 1.234,56
echo $number->format(1234.56, 0); // 1.235
echo $number->format(1234.56, 3); // 1.234,560

// Currency formatting
echo $number->currency(1234.56); // ₺1.234,56
echo $number->currency(1234.56, 'USD'); // $1.234,56
echo $english->currency(1234.56, 'EUR'); // €1,234.56

// Percentage formatting
echo $number->percent(0.75); // %75
echo $english->percent(0.75); // 75%

// Short form for large numbers
echo $number->shortForm(1500); // 1,5K
echo $number->shortForm(1500000); // 1,5M
echo $number->shortForm(1500000000); // 1,5B

// Spelling out numbers in words
echo $number->spellOut(42); // kırk iki
echo $english->spellOut(42); // forty-two

// Ordinal numbers
echo $number->ordinal(1); // 1.
echo $english->ordinal(1); // 1st

// Roman numerals
echo $number->roman(1984); // MCMLXXXIV

// Static methods
echo Number::isEven(42); // true
echo Number::isOdd(43); // true
```

## Requirements

- PHP 8.2 or higher
- Kint (automatically required through Composer)
- PHP Intl extension (for locale-specific string sorting and number formatting)

## License

MIT 