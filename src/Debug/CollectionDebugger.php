<?php

namespace Scriptmancer\Debug;

use Scriptmancer\Collections\Collection;

class CollectionDebugger
{
    /**
     * Create a new collection debugger instance.
     *
     * @param  \Scriptmancer\Collections\Collection  $collection  The collection instance.
     */
    public function __construct(
        protected Collection $collection
    ) {
    }

    /**
     * Dump the collection and continue execution.
     *
     * @return \Scriptmancer\Collections\Collection
     */
    public function dump(): Collection
    {
        d($this->collection->all());

        return $this->collection;
    }

    /**
     * Dump the collection and die.
     */
    public function dd(): void
    {
        dd($this->collection->all());
        
        exit(1); // This line is never reached but ensures compatibility with PHP < 8.1
    }

    /**
     * Dump the collection as JSON and continue execution.
     *
     * @param int $options
     * @return \Scriptmancer\Collections\Collection
     */
    public function dumpJson(int $options = 0): Collection
    {
        d($this->collection->toJson($options));

        return $this->collection;
    }

    /**
     * Print a visual representation of the collection.
     *
     * @return \Scriptmancer\Collections\Collection
     */
    public function table(): Collection
    {
        $items = $this->collection->all();
        
        if (empty($items)) {
            echo "Empty collection\n";
            return $this->collection;
        }

        // Determine if collection contains arrays/objects
        $firstItem = reset($items);
        $isAssociative = is_array($firstItem) || is_object($firstItem);
        
        if ($isAssociative) {
            $this->printAssociativeTable($items);
        } else {
            $this->printSimpleTable($items);
        }

        return $this->collection;
    }

    /**
     * Print a simple table for non-associative collections.
     *
     * @param array $items
     */
    protected function printSimpleTable(array $items): void
    {
        echo "┌───────────┬─────────────┐\n";
        echo "│ Index     │ Value       │\n";
        echo "├───────────┼─────────────┤\n";
        
        foreach ($items as $key => $value) {
            $keyStr = is_string($key) ? $key : "#".$key;
            $valueStr = $this->formatValue($value);
            printf("│ %-9s │ %-11s │\n", substr($keyStr, 0, 9), substr($valueStr, 0, 11));
        }
        
        echo "└───────────┴─────────────┘\n";
    }

    /**
     * Print a table for associative collections (containing arrays or objects).
     *
     * @param array $items
     */
    protected function printAssociativeTable(array $items): void
    {
        // Get all keys from all items
        $keys = [];
        foreach ($items as $item) {
            $itemKeys = is_object($item) ? get_object_vars($item) : $item;
            foreach ($itemKeys as $key => $value) {
                if (!in_array($key, $keys)) {
                    $keys[] = $key;
                }
            }
        }
        
        if (empty($keys)) {
            echo "Collection contains empty items\n";
            return;
        }
        
        // Print header
        echo "┌───────────";
        foreach ($keys as $key) {
            echo "┬─────────────";
        }
        echo "┐\n";
        
        echo "│ Index     ";
        foreach ($keys as $key) {
            printf("│ %-11s", substr($key, 0, 11));
        }
        echo "│\n";
        
        echo "├───────────";
        foreach ($keys as $key) {
            echo "┼─────────────";
        }
        echo "┤\n";
        
        // Print rows
        foreach ($items as $index => $item) {
            $indexStr = is_string($index) ? $index : "#".$index;
            printf("│ %-9s ", substr($indexStr, 0, 9));
            
            foreach ($keys as $key) {
                $value = is_object($item) 
                    ? ($item->$key ?? null)
                    : ($item[$key] ?? null);
                
                $valueStr = $this->formatValue($value);
                printf("│ %-11s", substr($valueStr, 0, 11));
            }
            
            echo "│\n";
        }
        
        echo "└───────────";
        foreach ($keys as $key) {
            echo "┴─────────────";
        }
        echo "┘\n";
    }

    /**
     * Format a value for display in the table.
     *
     * @param mixed $value
     * @return string
     */
    protected function formatValue(mixed $value): string
    {
        return match(true) {
            is_null($value) => 'null',
            is_bool($value) => $value ? 'true' : 'false',
            is_array($value) => 'Array['.count($value).']',
            is_object($value) => get_class($value),
            is_string($value) && strlen($value) > 11 => substr($value, 0, 8).'...',
            default => (string) $value
        };
    }

    /**
     * Count occurrences of values in the collection.
     *
     * @param string|null $key
     * @return array
     */
    public function countValues(?string $key = null): array
    {
        $values = $key ? $this->collection->pluck($key) : $this->collection;
        $counts = [];
        
        foreach ($values as $value) {
            $valueKey = is_scalar($value) ? $value : serialize($value);
            $counts[$valueKey] = ($counts[$valueKey] ?? 0) + 1;
        }

        d($counts);
        
        return $counts;
    }

    /**
     * Print statistics about the collection.
     *
     * @return \Scriptmancer\Collections\Collection
     */
    public function stats(): Collection
    {
        $stats = [
            'count' => $this->collection->count(),
            'empty' => $this->collection->isEmpty(),
            'first' => $this->collection->first(),
            'last' => $this->collection->last(),
        ];
        
        // Calculate numeric stats if possible
        $numericItems = $this->collection->filter(fn($value) => is_numeric($value));
        
        if ($numericItems->count() > 0) {
            $stats['sum'] = $numericItems->sum();
            $stats['avg'] = $numericItems->avg();
            $stats['min'] = $numericItems->min();
            $stats['max'] = $numericItems->max();
            
            if (method_exists($this->collection, 'median')) {
                $stats['median'] = $numericItems->median();
            }
        }
        
        d($stats);
        
        return $this->collection;
    }
} 