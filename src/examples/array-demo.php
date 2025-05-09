<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Scriptmancer\Support\Arr;

// Basic array operations
echo "=== Basic Array Operations ===\n";
$array = ['apple', 'banana', 'cherry', 'date', 'elderberry'];
echo "Array: " . implode(', ', $array) . "\n";
echo "First: " . Arr::first($array) . "\n";
echo "Last: " . Arr::last($array) . "\n";
echo "Count: " . Arr::count($array) . "\n";
echo "Keys: " . implode(', ', Arr::keys($array)) . "\n";
echo "Values: " . implode(', ', Arr::values($array)) . "\n";

// Get, set and has
echo "\n=== Get, Set and Has ===\n";
$user = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'roles' => ['admin', 'editor']
];
echo "Get 'name': " . Arr::get($user, 'name') . "\n";
echo "Get 'age' with default: " . Arr::get($user, 'age', 'not specified') . "\n";
echo "Has 'email': " . (Arr::has($user, 'email') ? 'true' : 'false') . "\n";
echo "Has 'age': " . (Arr::has($user, 'age') ? 'true' : 'false') . "\n";

// Functional operations
echo "\n=== Functional Operations ===\n";
$numbers = [1, 2, 3, 4, 5];
echo "Original numbers: " . implode(', ', $numbers) . "\n";

$doubled = Arr::map($numbers, function ($n) {
    return $n * 2;
});
echo "Doubled: " . implode(', ', $doubled) . "\n";

$evens = Arr::filter($numbers, function ($n) {
    return $n % 2 === 0;
});
echo "Even numbers: " . implode(', ', $evens) . "\n";

$sum = Arr::reduce($numbers, function ($carry, $n) {
    return $carry + $n;
}, 0);
echo "Sum: " . $sum . "\n";

// Array transformations
echo "\n=== Array Transformations ===\n";
$duplicate = [1, 2, 2, 3, 3, 3, 4, 5, 5];
echo "With duplicates: " . implode(', ', $duplicate) . "\n";
echo "Unique: " . implode(', ', Arr::unique($duplicate)) . "\n";

$unsorted = [5, 3, 1, 4, 2];
echo "Unsorted: " . implode(', ', $unsorted) . "\n";
echo "Sorted: " . implode(', ', Arr::sort($unsorted)) . "\n";
echo "Reversed: " . implode(', ', Arr::reverse($unsorted)) . "\n";

// Chunk and slice
echo "\n=== Chunk and Slice ===\n";
$letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
echo "Original: " . implode(', ', $letters) . "\n";

$chunks = Arr::chunk($letters, 3);
echo "Chunked (size 3):\n";
foreach ($chunks as $index => $chunk) {
    echo "  Chunk " . ($index + 1) . ": " . implode(', ', $chunk) . "\n";
}

echo "Slice (2, 4): " . implode(', ', Arr::slice($letters, 2, 4)) . "\n";

// Push, pop, etc.
echo "\n=== Push, Pop, Shift, Unshift ===\n";
$stack = [1, 2, 3];
echo "Original: " . implode(', ', $stack) . "\n";

$stack = Arr::push($stack, 4, 5);
echo "After push(4, 5): " . implode(', ', $stack) . "\n";

$stack = Arr::prepend($stack, 0);
echo "After prepend(0): " . implode(', ', $stack) . "\n";

// Random
echo "\n=== Random ===\n";
$deck = ['Ace', 'King', 'Queen', 'Jack', '10', '9', '8', '7'];
echo "Deck: " . implode(', ', $deck) . "\n";
echo "Random card: " . Arr::random($deck) . "\n";
echo "3 random cards: " . implode(', ', Arr::random($deck, 3)) . "\n";

// Merge
echo "\n=== Merge ===\n";
$array1 = ['apple', 'banana'];
$array2 = ['cherry', 'date'];
echo "Array 1: " . implode(', ', $array1) . "\n";
echo "Array 2: " . implode(', ', $array2) . "\n";
echo "Merged: " . implode(', ', Arr::merge($array1, $array2)) . "\n";

// Group by
echo "\n=== Group By ===\n";
$people = [
    ['name' => 'John', 'age' => 25],
    ['name' => 'Jane', 'age' => 30],
    ['name' => 'Bob', 'age' => 25],
    ['name' => 'Alice', 'age' => 30]
];
$grouped = Arr::groupBy($people, 'age');
echo "Grouped by age:\n";
foreach ($grouped as $age => $group) {
    $names = Arr::map($group, function ($person) {
        return $person['name'];
    });
    echo "  Age " . $age . ": " . implode(', ', $names) . "\n";
}

// Every and some
echo "\n=== Every and Some ===\n";
$allPositive = [1, 2, 3, 4, 5];
$someNegative = [1, 2, -3, 4, 5];
echo "All positive numbers: " . implode(', ', $allPositive) . "\n";
echo "Every item > 0: " . (Arr::every($allPositive, function ($n) {
    return $n > 0;
}) ? 'true' : 'false') . "\n";

echo "Some negative numbers: " . implode(', ', $someNegative) . "\n";
echo "Every item > 0: " . (Arr::every($someNegative, function ($n) {
    return $n > 0;
}) ? 'true' : 'false') . "\n";
echo "Some item > 3: " . (Arr::some($someNegative, function ($n) {
    return $n > 3;
}) ? 'true' : 'false') . "\n";

// String sorting with Collator
echo "\n=== Locale-specific String Sorting ===\n";

// Turkish alphabet sorting
echo "Turkish alphabet sorting:\n";
$turkish_words = ['şeker', 'armut', 'çilek', 'elma', 'üzüm', 'ıspanak', 'portakal', 'ördek'];
echo "Original: " . implode(', ', $turkish_words) . "\n";

// Regular sort (doesn't handle Turkish characters correctly)
$regular_sort = $turkish_words;
sort($regular_sort);
echo "Regular sort: " . implode(', ', $regular_sort) . "\n";

// Collator-based sort with Turkish locale
$tr_sort = Arr::string_sort($turkish_words, 'tr_TR');
echo "Turkish sort (tr_TR): " . implode(', ', $tr_sort) . "\n";

// Collator-based sort with English locale
$en_sort = Arr::string_sort($turkish_words, 'en_US');
echo "English sort (en_US): " . implode(', ', $en_sort) . "\n";

// Sorting arrays of associative arrays by a field
echo "\nSorting associative arrays by field:\n";
$countries = [
    ['name' => 'Türkiye', 'code' => 'TR'],
    ['name' => 'Şili', 'code' => 'CL'],
    ['name' => 'Almanya', 'code' => 'DE'],
    ['name' => 'Çin', 'code' => 'CN'],
    ['name' => 'İngiltere', 'code' => 'GB'],
    ['name' => 'İspanya', 'code' => 'ES'],
    ['name' => 'Özbekistan', 'code' => 'UZ']
];

echo "Countries (unsorted):\n";
foreach ($countries as $country) {
    echo "  {$country['name']} ({$country['code']})\n";
}

// Sort countries by name using Collator
$sorted_countries = Arr::string_sort($countries, 'tr_TR', 'name');

echo "\nCountries (sorted with tr_TR Collator):\n";
foreach ($sorted_countries as $country) {
    echo "  {$country['name']} ({$country['code']})\n";
}

// Sorting associative arrays by key
echo "\nSorting associative arrays by key:\n";
$month_names = [
    'Şubat' => 2,
    'Nisan' => 4,
    'Ocak' => 1,
    'Mart' => 3,
    'Temmuz' => 7,
    'Haziran' => 6,
    'Mayıs' => 5,
    'Ağustos' => 8,
    'Eylül' => 9,
    'Ekim' => 10,
    'Kasım' => 11,
    'Aralık' => 12
];

echo "Month names (unsorted):\n";
foreach ($month_names as $name => $number) {
    echo "  $name: $number\n";
}

// Sort months by keys using Collator
$sorted_months = Arr::string_sort_by_key($month_names, 'tr_TR');

echo "\nMonth names (sorted with tr_TR Collator):\n";
foreach ($sorted_months as $name => $number) {
    echo "  $name: $number\n";
}

// Additional Arr utility examples

echo "\n=== Arr Utility Extensions ===\n";

// flatten
$multi = [1, [2, [3, 4]], 5];
echo "Flatten: " . implode(', ', Arr::flatten($multi)) . "\n";

// dot/undot
$assoc = ['a' => ['b' => ['c' => 1]], 'x' => 2];
$dotted = Arr::dot($assoc);
echo "Dot: "; print_r($dotted);
$undotted = Arr::undot($dotted);
echo "Undot: "; print_r($undotted);

// pluck
$people = [ ['name' => 'Alice'], ['name' => 'Bob'] ];
echo "Pluck names: " . implode(', ', Arr::pluck($people, 'name')) . "\n";

// except/only
$assoc = ['a' => 1, 'b' => 2, 'c' => 3];
echo "Except b: "; print_r(Arr::except($assoc, 'b'));
echo "Only a,c: "; print_r(Arr::only($assoc, ['a', 'c']));

// forget
$nested = ['a' => ['b' => ['c' => 1]], 'x' => 2];
echo "Forget a.b.c: "; print_r(Arr::forget($nested, 'a.b.c'));

// pull
$arr = ['foo' => 'bar', 'baz' => 'qux'];
echo "Pull foo: " . Arr::pull($arr, 'foo') . ", Remaining: "; print_r($arr);

// random (multi)
$nums = range(1, 10);
echo "Random 3: "; print_r(Arr::random($nums, 3));

// shuffle
$shuffled = Arr::shuffle($nums);
echo "Shuffled: "; print_r($shuffled);

// isAssoc
$assoc = ['a' => 1, 'b' => 2]; $indexed = [1, 2];
echo "isAssoc (assoc): ".(Arr::isAssoc($assoc)?'true':'false')."\n";
echo "isAssoc (indexed): ".(Arr::isAssoc($indexed)?'true':'false')."\n";

// collapse
$toCollapse = [[1,2],[3,4],[5]];
echo "Collapse: "; print_r(Arr::collapse($toCollapse));

// partition
$nums = [1,2,3,4,5];
[$even, $odd] = Arr::partition($nums, fn($v)=>$v%2==0);
echo "Partition even/odd: "; print_r([$even, $odd]);

// where
$filtered = Arr::where($assoc, fn($v, $k) => $v > 1);
echo "Where >1: "; print_r($filtered);

// sortBy
$toSort = ['a' => 3, 'b' => 1, 'c' => 2];
echo "SortBy value asc: "; print_r(Arr::sortBy($toSort, fn($a,$b)=>$a<=>$b));

// hasAny/hasAll
$keys = ['a','b','z'];
echo "hasAny: ".(Arr::hasAny($assoc, $keys)?'true':'false')."\n";
echo "hasAll: ".(Arr::hasAll($assoc, $keys)?'true':'false')."\n";

// chunk
$chunked = Arr::chunk($nums, 2);
echo "Chunked (2): "; print_r($chunked);

echo "\n=== Test Complete ===\n";