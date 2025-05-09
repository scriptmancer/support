<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Scriptmancer\Support\Pipeline;

echo "=== Pipeline Pattern Examples ===\n\n";

// Example 1: Basic pipeline with closures
echo "Example 1: Basic pipeline with closures\n";
echo "-----------------------------------\n";

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

echo "Result: {$result}\n\n";

// Example 2: Transforming data through a pipeline
echo "Example 2: Transforming data through a pipeline\n";
echo "------------------------------------------\n";

// Define some pipe classes
class UppercasePipe
{
    public function handle($data, $next)
    {
        $data = strtoupper($data);
        return $next($data);
    }
}

class ReversePipe
{
    public function handle($data, $next)
    {
        $data = strrev($data);
        return $next($data);
    }
}

class TrimPipe
{
    public function handle($data, $next)
    {
        $data = trim($data);
        return $next($data);
    }
}

$result = Pipeline::make('  hello world  ')
    ->through([
        TrimPipe::class,
        UppercasePipe::class,
        ReversePipe::class,
    ])
    ->thenReturn();

echo "Original: '  hello world  '\n";
echo "Transformed: '{$result}'\n\n";

// Example 3: Using the pipeline helper
echo "Example 3: Using the pipeline helper\n";
echo "--------------------------------\n";

$numbers = [1, 2, 3, 4, 5];

$result = pipeline($numbers)
    ->through([
        function ($numbers, $next) {
            // Filter even numbers
            return $next(array_filter($numbers, fn($n) => $n % 2 === 0));
        },
        function ($numbers, $next) {
            // Square the numbers
            return $next(array_map(fn($n) => $n * $n, $numbers));
        },
        function ($numbers, $next) {
            // Sum the results
            return $next(array_sum($numbers));
        },
    ])
    ->thenReturn();

echo "Original numbers: " . implode(', ', $numbers) . "\n";
echo "Result (sum of squares of even numbers): {$result}\n\n";

// Example 4: Using a custom method name
echo "Example 4: Using a custom method name\n";
echo "--------------------------------\n";

class AddFive
{
    public function process($number, $next)
    {
        return $next($number + 5);
    }
}

class MultiplyByTwo
{
    public function process($number, $next)
    {
        return $next($number * 2);
    }
}

class SubtractThree
{
    public function process($number, $next)
    {
        return $next($number - 3);
    }
}

$result = Pipeline::make(10)
    ->through([
        AddFive::class,      // 10 + 5 = 15
        MultiplyByTwo::class, // 15 * 2 = 30
        SubtractThree::class, // 30 - 3 = 27
    ])
    ->via('process')
    ->thenReturn();

echo "Starting with: 10\n";
echo "After pipeline: {$result}\n\n";

echo "=== Pipeline Examples Complete ===\n"; 