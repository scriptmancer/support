<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Nazim\Support\Date;
use Nazim\Support\DateObject;

// Different ways to create date objects
echo "=== Creating Date Objects ===\n";
$now = new DateObject();
echo "Now: " . $now . "\n";

$fromString = date_obj('2023-05-15 10:30:00');
echo "From string: " . $fromString . "\n";

$fromFormat = new DateObject(Date::parseFromFormat('Y-m-d', '2023-12-25'));
echo "From format: " . $fromFormat . "\n";

$today = DateObject::today();
echo "Today: " . $today . "\n";

$tomorrow = DateObject::tomorrow();
echo "Tomorrow: " . $tomorrow . "\n";

$yesterday = DateObject::yesterday();
echo "Yesterday: " . $yesterday . "\n";

// Demonstrating immutability
echo "\n=== Immutability Tests ===\n";
$original = date_obj('2023-01-01');
echo "Original: " . $original . "\n";

$addDays = $original->addDays(5);
echo "After addDays(5): " . $addDays . "\n";
echo "Original remains unchanged: " . $original . "\n";

$addMonths = $original->addMonths(2);
echo "After addMonths(2): " . $addMonths . "\n";
echo "Original remains unchanged: " . $original . "\n";

// Method chaining
echo "\n=== Method Chaining ===\n";
$chained = $original->addDays(10)->addMonths(1)->addYears(1);
echo "Original: " . $original . "\n";
echo "After chaining: " . $chained . "\n";

// Date Comparisons
echo "\n=== Date Comparisons ===\n";
$date1 = date_obj('2023-05-15');
$date2 = date_obj('2023-05-15 13:45:00');
$date3 = date_obj('2023-06-15');

echo "Date1: " . $date1 . "\n";
echo "Date2: " . $date2 . "\n";
echo "Date3: " . $date3 . "\n";

echo "isSameDay(date1, date2): " . ($date1->isSameDay($date2) ? 'true' : 'false') . "\n";
echo "isSameDay(date1, date3): " . ($date1->isSameDay($date3) ? 'true' : 'false') . "\n";
echo "isSameMonth(date1, date3): " . ($date1->isSameMonth($date3) ? 'true' : 'false') . "\n";
echo "isSameYear(date1, date3): " . ($date1->isSameYear($date3) ? 'true' : 'false') . "\n";

// Date calculations
echo "\n=== Date Calculations ===\n";
$start = date_obj('2023-01-01');
$end = date_obj('2023-12-31');

$diff = $start->diff($end);
echo "Difference between " . $start . " and " . $end . ": " . 
     $diff->format('%R%a days, %m months, %y years') . "\n";

// Check date conditions
echo "\n=== Date Conditions ===\n";
$weekendCheck = date_obj('2023-05-13'); // A Saturday
echo $weekendCheck . " is weekend: " . ($weekendCheck->isWeekend() ? 'true' : 'false') . "\n";

$futureDate = date_obj()->addDays(10);
echo $futureDate . " is in future: " . ($futureDate->isFuture() ? 'true' : 'false') . "\n";

$pastDate = date_obj()->subDays(10);
echo $pastDate . " is in past: " . ($pastDate->isPast() ? 'true' : 'false') . "\n";

$middleDate = date_obj('2023-06-15');
echo $middleDate . " is between " . $start . " and " . $end . ": " . 
     ($middleDate->isBetween($start, $end) ? 'true' : 'false') . "\n";

// Date formatting
echo "\n=== Date Formatting ===\n";
$formatDate = date_obj('2023-05-15 14:30:45');
echo "Default format: " . $formatDate . "\n";
echo "Custom format (Y-m-d): " . $formatDate->format('Y-m-d') . "\n";
echo "Custom format (d/m/Y H:i): " . $formatDate->format('d/m/Y H:i') . "\n";
echo "Custom format (F j, Y, g:i a): " . $formatDate->format('F j, Y, g:i a') . "\n";

// More additions and subtractions
echo "\n=== More Date Operations ===\n";
$baseDate = date_obj('2023-05-15 10:00:00');
echo "Base date: " . $baseDate . "\n";
echo "Add 3 hours: " . $baseDate->addHours(3) . "\n";
echo "Subtract 30 minutes: " . $baseDate->subMinutes(30) . "\n";
echo "Add 1.5 years: " . $baseDate->addYears(1)->addMonths(6) . "\n";
echo "Subtract 1 year and 2 months: " . $baseDate->subYears(1)->subMonths(2) . "\n";

// Done
echo "\n=== Test Complete ===\n";
