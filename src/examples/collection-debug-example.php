<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Scriptmancer\Collections\Collection;

echo "\n=== Collection Debugging Examples ===\n\n";

// Create sample collections for testing
$numbers = collect([1, 2, 3, 4, 5, 5, 4, 3, 2, 1]);
$users = collect([
    ['id' => 1, 'name' => 'John', 'role' => 'Admin', 'age' => 28],
    ['id' => 2, 'name' => 'Jane', 'role' => 'User', 'age' => 34],
    ['id' => 3, 'name' => 'Dave', 'role' => 'Admin', 'age' => 41],
    ['id' => 4, 'name' => 'Sarah', 'role' => 'Manager', 'age' => 29],
]);

// Example 1: Basic debug dump
echo "Example 1: Basic debug dump\n";
echo "-------------------------\n";
$numbers->debug()->dump();
echo "\n";

// Example 2: Debug as JSON
echo "Example 2: Debug as JSON\n";
echo "-------------------------\n";
$users->debug()->dumpJson();
echo "\n";

// Example 3: Pretty printing tables - simple collection
echo "Example 3: Pretty printing tables - simple collection\n";
echo "-------------------------\n";
$numbers->debug()->table();
echo "\n";

// Example 4: Pretty printing tables - associative collection
echo "Example 4: Pretty printing tables - associative collection\n";
echo "-------------------------\n";
$users->debug()->table();
echo "\n";

// Example 5: Count values
echo "Example 5: Count values\n";
echo "-------------------------\n";
$numbers->debug()->countValues();
echo "\n";

// Example 6: Count specific field values
echo "Example 6: Count specific field values\n";
echo "-------------------------\n";
$users->debug()->countValues('role');
echo "\n";

// Example 7: Get statistics
echo "Example 7: Get statistics\n";
echo "-------------------------\n";
$numbers->debug()->stats();
echo "\n";

// Example 8: Using the helper function
echo "Example 8: Using the helper function\n";
echo "-------------------------\n";
collection_debug([10, 20, 30, 40, 50])->table();
echo "\n";

// Example 9: Chaining debug with other collection methods
echo "Example 9: Chaining debug with other methods\n";
echo "-------------------------\n";
$users
    ->where('role', 'Admin')
    ->debug()->dump()
    ->pluck('name')
    ->debug()->table();
echo "\n";

echo "=== Collection Debug Examples Complete ===\n"; 