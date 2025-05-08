<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Nazim\Support\Collections\Collection;

// Basic collection creation
echo "=== Basic Collection Usage ===\n";
$collection = collect([1, 2, 3, 4, 5]);
echo "Collection: " . json_encode($collection->all()) . "\n";
echo "Count: {$collection->count()}\n";
echo "First item: {$collection->first()}\n";
echo "Last item: {$collection->last()}\n";

// Map operation
echo "\n=== Map Operation ===\n";
$doubled = $collection->map(function ($item) {
    return $item * 2;
});
echo "Doubled: " . json_encode($doubled->all()) . "\n";

// Filter operation
echo "\n=== Filter Operation ===\n";
$evenNumbers = $collection->filter(function ($item) {
    return $item % 2 === 0;
});
echo "Even numbers: " . json_encode($evenNumbers->all()) . "\n";

// Associative array collection
echo "\n=== Associative Array Collection ===\n";
$users = collect([
    ['name' => 'John', 'age' => 25],
    ['name' => 'Jane', 'age' => 30],
    ['name' => 'Dave', 'age' => 28],
    ['name' => 'Emily', 'age' => 22],
]);
echo "Users: " . json_encode($users->all()) . "\n";

// Pluck specific values
echo "\n=== Pluck Method ===\n";
$names = $users->pluck('name');
echo "Names: " . json_encode($names->all()) . "\n";

// Pluck with keys
$namesByAge = $users->pluck('name', 'age');
echo "Names by age: " . json_encode($namesByAge->all()) . "\n";

// Sort collection
echo "\n=== Sorting ===\n";
$numbers = collect([5, 3, 1, 2, 4]);
echo "Unsorted: " . json_encode($numbers->all()) . "\n";
echo "Sorted: " . json_encode($numbers->sort()->all()) . "\n";
echo "Sorted descending: " . json_encode($numbers->sortDesc()->all()) . "\n";

// Sort by specific key
echo "\n=== Sort By ===\n";
$sortedByAge = $users->sortBy('age');
echo "Sorted by age: " . json_encode($sortedByAge->all()) . "\n";
$sortedByAgeDesc = $users->sortBy('age')->reverse();
echo "Sorted by age (desc): " . json_encode($sortedByAgeDesc->all()) . "\n";

// Collection aggregation
echo "\n=== Aggregation Methods ===\n";
$numbers = collect([1, 2, 3, 4, 5]);
echo "Sum: {$numbers->sum()}\n";
echo "Average: {$numbers->avg()}\n";
echo "Min: {$numbers->min()}\n";
echo "Max: {$numbers->max()}\n";

// Use with objects
echo "\n=== Collection with Objects ===\n";
class Person {
    public $name;
    public $age;
    
    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }
}

$people = collect([
    new Person('John', 25),
    new Person('Jane', 30),
    new Person('Dave', 28),
]);

$peopleNames = $people->pluck('name');
echo "People names: " . json_encode($peopleNames->all()) . "\n";

// Filtering objects
$adults = $people->filter(function ($person) {
    return $person->age >= 28;
});
echo "Adults: " . json_encode($adults->pluck('name')->all()) . "\n";

// Complex operations - chain multiple methods
echo "\n=== Chaining Methods ===\n";
$result = $people
    ->sortBy('age')
    ->map(function ($person) {
        $person->name = strtoupper($person->name);
        return $person;
    })
    ->pluck('name');
echo "Result of chained operations: " . json_encode($result->all()) . "\n";

// Transformative operations
echo "\n=== Transform Operation ===\n";
$collection = collect([1, 2, 3]);
$collection->transform(function ($item) {
    return $item * 10;
});
echo "Transformed collection: " . json_encode($collection->all()) . "\n";

// Collection manipulation
echo "\n=== Collection Manipulation ===\n";
$collection = collect([1, 2, 3]);
$collection->push(4);
echo "After push: " . json_encode($collection->all()) . "\n";

$first = $collection->shift();
echo "Shifted value: {$first}\n";
echo "After shift: " . json_encode($collection->all()) . "\n";

$collection->pop();
echo "After pop: " . json_encode($collection->all()) . "\n";

$collection->prepend(0);
echo "After prepend: " . json_encode($collection->all()) . "\n";

// Chunk the collection
echo "\n=== Chunk Method ===\n";
$collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);
$chunks = $collection->chunk(3);
echo "Chunks: " . json_encode($chunks->toArray()) . "\n";

// Combine method
echo "\n=== Combine Method ===\n";
$keys = collect(['name', 'age', 'city']);
$values = collect(['John', 25, 'New York']);
$combined = $keys->combine($values);
echo "Combined: " . json_encode($combined->all()) . "\n";

// GroupBy method
echo "\n=== GroupBy Method ===\n";
$students = collect([
    ['name' => 'John', 'grade' => 'A'],
    ['name' => 'Jane', 'grade' => 'B'],
    ['name' => 'Dave', 'grade' => 'A'],
    ['name' => 'Emily', 'grade' => 'B'],
]);
$grouped = $students->groupBy('grade');
echo "Grouped by grade: " . json_encode($grouped->toArray()) . "\n";

// Merge method
echo "\n=== Merge Method ===\n";
$collection1 = collect([1, 2, 3]);
$collection2 = collect([4, 5, 6]);
$merged = $collection1->merge($collection2);
echo "Merged: " . json_encode($merged->all()) . "\n";

// Union method
echo "\n=== Union Method ===\n";
$collection1 = collect(['a' => 1, 'b' => 2]);
$collection2 = collect(['a' => 3, 'c' => 4]);
$union = $collection1->union($collection2);
echo "Union: " . json_encode($union->all()) . "\n";

// Unique method
echo "\n=== Unique Method ===\n";
$collection = collect([1, 1, 2, 2, 3, 4, 2]);
$unique = $collection->unique();
echo "Unique: " . json_encode($unique->values()->all()) . "\n";

// Unique by key
$users = collect([
    ['id' => 1, 'name' => 'John', 'role' => 'Admin'],
    ['id' => 2, 'name' => 'Jane', 'role' => 'User'],
    ['id' => 3, 'name' => 'Dave', 'role' => 'Admin'],
]);
$uniqueByRole = $users->unique('role');
echo "Unique by role: " . json_encode($uniqueByRole->values()->all()) . "\n";

// Flatten method
echo "\n=== Flatten Method ===\n";
$collection = collect([
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9],
]);
$flattened = $collection->flatten();
echo "Flattened: " . json_encode($flattened->all()) . "\n";

// Only and except methods
echo "\n=== Only and Except Methods ===\n";
$collection = collect(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]);
$only = $collection->only(['a', 'c']);
echo "Only a and c: " . json_encode($only->all()) . "\n";

$except = $collection->except(['a', 'c']);
echo "Except a and c: " . json_encode($except->all()) . "\n";

// Take method
echo "\n=== Take Method ===\n";
$collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);
$take3 = $collection->take(3);
echo "Take 3: " . json_encode($take3->all()) . "\n";

$takeLast3 = $collection->take(-3);
echo "Take last 3: " . json_encode($takeLast3->all()) . "\n";

// Implode method
echo "\n=== Implode Method ===\n";
$collection = collect(['a', 'b', 'c']);
$imploded = $collection->implode(', ');
echo "Imploded: {$imploded}\n";

// JSON conversion
echo "\n=== JSON Conversion ===\n";
$collection = collect(['name' => 'John', 'age' => 25]);
$json = $collection->toJson();
echo "JSON: {$json}\n";

echo "\n=== Collection Examples Complete ===\n"; 