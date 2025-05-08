<?php

namespace Nazim\Support\Collections;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use ReturnTypeWillChange;
use Nazim\Support\Debug\CollectionDebugger;
use Nazim\Support\Arr;

class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * The items contained in the collection.
     */
    protected array $items = [];

    /**
     * Create a new collection.
     *
     * @param  mixed  $items
     */
    public function __construct(mixed $items = [])
    {
        $this->items = $this->getArrayableItems($items);
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Run a map over each of the items.
     *
     * @param  callable  $callback
     * @return static
     */
    public function map(callable $callback): static
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    /**
     * Run a filter over each of the items.
     *
     * @param  callable|null  $callback
     * @return static
     */
    public function filter(?callable $callback = null): static
    {
        if ($callback) {
            return new static(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
        }

        return new static(array_filter($this->items));
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_map(function ($value) {
            return $value instanceof self ? $value->toArray() : $value;
        }, $this->items);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists(mixed $key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet(mixed $key): mixed
    {
        return $this->items[$key];
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     */
    public function offsetSet(mixed $key, mixed $value): void
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  mixed  $key
     */
    public function offsetUnset(mixed $key): void
    {
        unset($this->items[$key]);
    }

    /**
     * Results array of items from Collection or Arrayable.
     *
     * @param  mixed  $items
     * @return array
     */
    protected function getArrayableItems(mixed $items): array
    {
        if (is_array($items)) {
            return $items;
        }

        if ($items instanceof self) {
            return $items->all();
        }

        if ($items instanceof \Traversable) {
            return iterator_to_array($items);
        }

        return (array) $items;
    }

    /**
     * Get the first item in the collection.
     *
     * @param callable|null $callback
     * @param mixed $default
     * @return mixed
     */
    public function first(?callable $callback = null, mixed $default = null): mixed
    {
        if (is_null($callback)) {
            if (empty($this->items)) {
                return $default;
            }
            
            foreach ($this->items as $item) {
                return $item;
            }
        }
        
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
        
        return $default;
    }

    /**
     * Get the last item in the collection.
     *
     * @param callable|null $callback
     * @param mixed $default
     * @return mixed
     */
    public function last(callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($this->items)) {
                return $default;
            }
            
            return end($this->items);
        }
        
        return $this->filter($callback)->last(null, $default);
    }

    /**
     * Get the values of a given key.
     *
     * @param string|array|null $value
     * @param string|null $key
     * @return static
     */
    public function pluck($value, $key = null)
    {
        $results = [];
        
        foreach ($this->items as $item) {
            $itemValue = is_array($item) ? $item[$value] : $item->$value;
            
            // If the key is null, we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = is_array($item) ? $item[$key] : $item->$key;
                
                $results[$itemKey] = $itemValue;
            }
        }
        
        return new static($results);
    }

    /**
     * Run a reduce operation on the collection.
     *
     * @param callable $callback
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        $result = $initial;
        
        foreach ($this->items as $key => $value) {
            $result = $callback($result, $value, $key);
        }
        
        return $result;
    }

    /**
     * Search the collection for a given value and return the corresponding key.
     *
     * @param mixed $value
     * @param bool $strict
     * @return mixed
     */
    public function search($value, $strict = false)
    {
        if (!is_callable($value)) {
            return array_search($value, $this->items, $strict);
        }
        
        foreach ($this->items as $key => $item) {
            if ($value($item, $key)) {
                return $key;
            }
        }
        
        return false;
    }

    /**
     * Get and remove the first item from the collection.
     *
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param mixed $value
     * @return $this
     */
    public function prepend($value)
    {
        array_unshift($this->items, $value);
        
        return $this;
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param mixed $value
     * @return $this
     */
    public function push($value)
    {
        $this->items[] = $value;
        
        return $this;
    }

    /**
     * Get and remove the last item from the collection.
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Determine if the collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Determine if the collection is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param mixed $items
     * @return static
     */
    public static function make($items = [])
    {
        return new static($items);
    }

    /**
     * Get the keys of the collection items.
     *
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->items));
    }

    /**
     * Run a reverse operation on the collection.
     *
     * @return static
     */
    public function reverse()
    {
        return new static(array_reverse($this->items, true));
    }

    /**
     * Slice the underlying collection array.
     *
     * @param int $offset
     * @param int|null $length
     * @return static
     */
    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->items, $offset, $length, true));
    }

    /**
     * Chunk the collection into chunks of the given size.
     *
     * @param int $size
     * @return static
     */
    public function chunk($size)
    {
        if ($size <= 0) {
            return new static;
        }
        
        $chunks = [];
        
        foreach (array_chunk($this->items, $size, true) as $chunk) {
            $chunks[] = new static($chunk);
        }
        
        return new static($chunks);
    }

    /**
     * Sort the collection.
     *
     * @param callable|null $callback
     * @return static
     */
    public function sort(callable $callback = null)
    {
        $items = $this->items;
        
        $callback
            ? uasort($items, $callback)
            : asort($items);
        
        return new static($items);
    }

    /**
     * Sort the collection by the given key.
     *
     * @param string $key
     * @param int $options
     * @return static
     */
    public function sortBy($key, $options = SORT_REGULAR)
    {
        $items = $this->items;
        
        $results = [];
        
        foreach ($items as $index => $item) {
            $results[$index] = is_array($item) ? $item[$key] : $item->$key;
        }
        
        asort($results, $options);
        
        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model, then return a new collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $items[$key];
        }
        
        return new static($results);
    }

    /**
     * Sort the collection in descending order.
     *
     * @param callable|null $callback
     * @return static
     */
    public function sortDesc(callable $callback = null)
    {
        $items = $this->items;
        
        $callback
            ? uasort($items, function ($a, $b) use ($callback) {
                return $callback($b, $a);
            })
            : arsort($items);
        
        return new static($items);
    }

    /**
     * Sort the collection keys.
     *
     * @param int $options
     * @return static
     */
    public function sortKeys($options = SORT_REGULAR)
    {
        $items = $this->items;
        
        ksort($items, $options);
        
        return new static($items);
    }

    /**
     * Sort the collection keys in descending order.
     *
     * @param int $options
     * @return static
     */
    public function sortKeysDesc($options = SORT_REGULAR)
    {
        $items = $this->items;
        
        krsort($items, $options);
        
        return new static($items);
    }

    /**
     * Take the first or last {$limit} items.
     *
     * @param int $limit
     * @return static
     */
    public function take($limit)
    {
        if ($limit < 0) {
            return $this->slice($limit, abs($limit));
        }
        
        return $this->slice(0, $limit);
    }

    /**
     * Merge the collection with the given items.
     *
     * @param mixed $items
     * @return static
     */
    public function merge($items)
    {
        return new static(array_merge($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Combine the values of the collection, as keys, with the given values.
     *
     * @param mixed $values
     * @return static
     */
    public function combine($values)
    {
        return new static(array_combine($this->all(), $this->getArrayableItems($values)));
    }

    /**
     * Union the collection with the given items.
     *
     * @param mixed $items
     * @return static
     */
    public function union($items)
    {
        return new static($this->items + $this->getArrayableItems($items));
    }

    /**
     * Get the values of the collection.
     *
     * @return static
     */
    public function values()
    {
        return new static(array_values($this->items));
    }

    /**
     * Get the unique items in the collection.
     *
     * @param string|callable|null $key
     * @param bool $strict
     * @return static
     */
    public function unique($key = null, $strict = false)
    {
        if (is_null($key)) {
            return new static(array_unique($this->items, SORT_REGULAR));
        }
        
        $seen = [];
        $result = [];
        
        foreach ($this->items as $keyValue => $item) {
            $valueToCheck = is_callable($key) 
                ? $key($item, $keyValue) 
                : (is_array($item) ? $item[$key] : $item->$key);
            
            if (!in_array($valueToCheck, $seen, $strict)) {
                $seen[] = $valueToCheck;
                $result[$keyValue] = $item;
            }
        }
        
        return new static($result);
    }

    /**
     * Adds an item to the collection if it doesn't exist.
     *
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function add($key, $value)
    {
        if (!$this->offsetExists($key)) {
            $this->offsetSet($key, $value);
        }
        
        return $this;
    }

    /**
     * Executes a callback over each item.
     *
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
        
        return $this;
    }

    /**
     * Get the average value of a given key.
     *
     * @param callable|string|null $callback
     * @return mixed
     */
    public function avg($callback = null)
    {
        if ($count = $this->count()) {
            return $this->sum($callback) / $count;
        }
    }

    /**
     * Get the sum of the given values.
     *
     * @param callable|string|null $callback
     * @return mixed
     */
    public function sum($callback = null)
    {
        if (is_null($callback)) {
            return array_sum($this->items);
        }
        
        $callback = $this->valueRetriever($callback);
        
        return $this->reduce(function ($result, $item, $key) use ($callback) {
            return $result + $callback($item, $key);
        }, 0);
    }

    /**
     * Get the max value of a given key.
     *
     * @param callable|string|null $callback
     * @return mixed
     */
    public function max($callback = null)
    {
        $callback = $this->valueRetriever($callback);
        
        return $this->reduce(function ($result, $item, $key) use ($callback) {
            $value = $callback($item, $key);
            
            return is_null($result) || $value > $result ? $value : $result;
        });
    }

    /**
     * Get the min value of a given key.
     *
     * @param callable|string|null $callback
     * @return mixed
     */
    public function min($callback = null)
    {
        $callback = $this->valueRetriever($callback);
        
        return $this->reduce(function ($result, $item, $key) use ($callback) {
            $value = $callback($item, $key);
            
            return is_null($result) || $value < $result ? $value : $result;
        });
    }

    /**
     * Get a value retrieving callback.
     *
     * @param callable|string|null $value
     * @return callable
     */
    protected function valueRetriever($value)
    {
        if (is_null($value)) {
            return function ($item) {
                return $item;
            };
        }
        
        if (is_string($value)) {
            return function ($item) use ($value) {
                return is_array($item) ? $item[$value] : $item->$value;
            };
        }
        
        return $value;
    }

    /**
     * Transform each item in the collection using a callback.
     * 
     * @param callable $callback
     * @return $this
     */
    public function transform(callable $callback)
    {
        $this->items = $this->map($callback)->all();
        
        return $this;
    }

    /**
     * Returns only the values where the key exists in all of the given arrays.
     *
     * @param mixed $items
     * @return static
     */
    public function intersect($items)
    {
        return new static(array_intersect($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Returns all items in the collection except for those with the specified keys.
     *
     * @param mixed $keys
     * @return static
     */
    public function except($keys)
    {
        $keys = $this->getArrayableItems($keys);
        
        return $this->filter(function ($value, $key) use ($keys) {
            return !in_array($key, $keys);
        });
    }

    /**
     * Returns only the items from the collection with the specified keys.
     *
     * @param mixed $keys
     * @return static
     */
    public function only($keys)
    {
        $keys = $this->getArrayableItems($keys);
        
        return $this->filter(function ($value, $key) use ($keys) {
            return in_array($key, $keys);
        });
    }

    /**
     * Group an associative array by a field or using a callback.
     *
     * @param callable|string $groupBy
     * @return static
     */
    public function groupBy($groupBy)
    {
        $groupBy = $this->valueRetriever($groupBy);
        
        $results = [];
        
        foreach ($this->items as $key => $value) {
            $groupKey = $groupBy($value, $key);
            
            if (!isset($results[$groupKey])) {
                $results[$groupKey] = new static;
            }
            
            $results[$groupKey]->offsetSet($key, $value);
        }
        
        return new static($results);
    }

    /**
     * Flatten a multi-dimensional collection into a single level.
     *
     * @param int $depth
     * @return static
     */
    public function flatten($depth = INF)
    {
        return new static($this->flattenItems($this->items, $depth));
    }

    /**
     * Get a flattened array of the items in the collection.
     *
     * @param array $items
     * @param int $depth
     * @return array
     */
    protected function flattenItems(array $items, $depth = INF)
    {
        $result = [];
        
        foreach ($items as $item) {
            if (is_array($item) && $depth > 0) {
                $result = array_merge($result, $this->flattenItems($item, $depth - 1));
            } else {
                $result[] = $item;
            }
        }
        
        return $result;
    }

    /**
     * Implode the values into a string.
     *
     * @param string $glue
     * @param string|callable|null $key
     * @return string
     */
    public function implode($glue, $key = null)
    {
        if ($key) {
            $callback = $this->valueRetriever($key);
            
            return implode($glue, $this->map($callback)->all());
        }
        
        return implode($glue, $this->items);
    }

    /**
     * Convert the collection to JSON.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param int $options
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Create a collection by invoking the callback a given number of times.
     *
     * @param int $number
     * @param callable $callback
     * @return static
     */
    public static function times($number, callable $callback = null)
    {
        if ($number < 1) {
            return new static;
        }
        
        if (is_null($callback)) {
            return new static(range(1, $number));
        }
        
        return new static(array_map($callback, range(1, $number)));
    }
    
    /**
     * Partition the collection into two arrays using the given callback.
     *
     * @param callable $callback
     * @return static
     */
    public function partition(callable $callback)
    {
        $partitions = [new static, new static];
        
        foreach ($this->items as $key => $item) {
            $partitions[(int) !$callback($item, $key)][$key] = $item;
        }
        
        return new static($partitions);
    }
    
    /**
     * Get a random item(s) from the collection.
     *
     * @param int|null $number
     * @return static|mixed
     */
    public function random($number = null)
    {
        if (is_null($number)) {
            return $this->items[array_rand($this->items)];
        }
        
        return new static(array_intersect_key(
            $this->items,
            array_flip(array_rand($this->items, $number))
        ));
    }
    
    /**
     * Get the median of a given key.
     *
     * @param string|null $key
     * @return mixed
     */
    public function median($key = null)
    {
        $values = $this->pluck($key)->filter(function ($value) {
            return !is_null($value);
        })->sort()->values();
        
        $count = $values->count();
        
        if ($count === 0) {
            return null;
        }
        
        $middle = (int) ($count / 2);
        
        if ($count % 2) {
            return $values[$middle];
        }
        
        return ($values[$middle - 1] + $values[$middle]) / 2;
    }
    
    /**
     * Get the mode of a given key.
     *
     * @param string|null $key
     * @return array|null
     */
    public function mode($key = null)
    {
        $values = $this->pluck($key)->filter(function ($value) {
            return !is_null($value);
        });
        
        $counts = new static;
        
        foreach ($values as $value) {
            $counts[$value] = isset($counts[$value]) ? $counts[$value] + 1 : 1;
        }
        
        if ($counts->isEmpty()) {
            return null;
        }
        
        $highestCount = $counts->max();
        
        return $counts->filter(function ($count) use ($highestCount) {
            return $count == $highestCount;
        })->keys()->all();
    }
    
    /**
     * Cross join with the given arrays or collections.
     *
     * @param mixed ...$arrays
     * @return static
     */
    public function crossJoin(...$arrays)
    {
        $arrays = array_map(function ($array) {
            return $this->getArrayableItems($array);
        }, $arrays);
        
        $results = [[]];
        
        foreach ([$this->items, ...$arrays] as $index => $array) {
            $temp = [];
            
            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;
                    $temp[] = $product;
                }
            }
            
            $results = $temp;
        }
        
        return new static($results);
    }
    
    /**
     * Pad collection to the specified length with a value.
     *
     * @param int $size
     * @param mixed $value
     * @return static
     */
    public function pad($size, $value)
    {
        return new static(array_pad($this->items, $size, $value));
    }
    
    /**
     * Get the items in the collection that are not present in the given items.
     *
     * @param mixed $items
     * @return static
     */
    public function diff($items)
    {
        return new static(array_diff($this->items, $this->getArrayableItems($items)));
    }
    
    /**
     * Execute a function on every nth element.
     *
     * @param int $step
     * @param callable $callback
     * @return $this
     */
    public function nth($step, callable $callback)
    {
        $position = 0;
        
        foreach ($this->items as $key => $item) {
            if ($position % $step === 0) {
                $callback($item, $key);
            }
            
            $position++;
        }
        
        return $this;
    }
    
    /**
     * Convert the collection to a "dot" notation array.
     *
     * @param string $prepend
     * @return static
     */
    public function dot($prepend = '')
    {
        $results = [];
        
        foreach ($this->items as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, (new static($value))->dot($prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }
        
        return new static($results);
    }
    
    /**
     * Get a collection containing the items for which the callback returns a falsy value.
     *
     * @param callable $callback
     * @return static
     */
    public function reject(callable $callback)
    {
        return $this->filter(function ($value, $key) use ($callback) {
            return !$callback($value, $key);
        });
    }
    
    /**
     * Create a collection with the given range.
     *
     * @param int $from
     * @param int $to
     * @return static
     */
    public static function range($from, $to)
    {
        return new static(range($from, $to));
    }
    
    /**
     * Collapse the collection of items into a single array.
     *
     * @return static
     */
    public function collapse()
    {
        $results = [];
        
        foreach ($this->items as $values) {
            if ($values instanceof self) {
                $values = $values->all();
            } elseif (!is_array($values)) {
                continue;
            }
            
            $results = array_merge($results, $values);
        }
        
        return new static($results);
    }
    
    /**
     * Flip the items in the collection.
     *
     * @return static
     */
    public function flip()
    {
        return new static(array_flip($this->items));
    }
    
    /**
     * "Paginate" the collection by slicing it into a smaller collection.
     *
     * @param int $page
     * @param int $perPage
     * @return static
     */
    public function forPage($page, $perPage)
    {
        $offset = max(0, ($page - 1) * $perPage);
        
        return $this->slice($offset, $perPage);
    }
    
    /**
     * Determine if all items in the collection pass the given test.
     *
     * @param string|callable $key
     * @param mixed $operator
     * @param mixed $value
     * @return bool
     */
    public function every($key, $operator = null, $value = null)
    {
        if (func_num_args() === 1) {
            $callback = $this->valueRetriever($key);
            
            foreach ($this->items as $k => $v) {
                if (!$callback($v, $k)) {
                    return false;
                }
            }
            
            return true;
        }
        
        return $this->every($this->operatorForWhere(...func_get_args()));
    }
    
    /**
     * Get a callback that represents a where condition.
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return callable
     */
    protected function operatorForWhere($key, $operator = null, $value = null)
    {
        if (func_num_args() === 1) {
            return function ($item) use ($key) {
                return $item == $key;
            };
        }
        
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        
        return function ($item) use ($key, $operator, $value) {
            $retrieved = is_array($item) ? $item[$key] : $item->$key;
            
            switch ($operator) {
                case '=':
                case '==':
                    return $retrieved == $value;
                case '!=':
                case '<>':
                    return $retrieved != $value;
                case '<':
                    return $retrieved < $value;
                case '>':
                    return $retrieved > $value;
                case '<=':
                    return $retrieved <= $value;
                case '>=':
                    return $retrieved >= $value;
                case '===':
                    return $retrieved === $value;
                case '!==':
                    return $retrieved !== $value;
                default:
                    return false;
            }
        };
    }
    
    /**
     * Wrap the given value in a collection if applicable.
     *
     * @param mixed $value
     * @return static
     */
    public static function wrap($value)
    {
        return $value instanceof self
            ? new static($value)
            : new static(Arr::wrap($value));
    }
    
    /**
     * Find a model in the collection by key.
     *
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function find($key, $default = null)
    {
        if ($key instanceof self) {
            $key = $key->first();
        }
        
        return $this->offsetExists($key) ? $this->items[$key] : $default;
    }

    /**
     * Get a debugger for the collection.
     *
     * @return \Nazim\Support\Debug\CollectionDebugger
     */
    public function debug(): CollectionDebugger
    {
        return new CollectionDebugger($this);
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param string $key
     * @param mixed $operator
     * @param mixed $value
     * @return static
     */
    public function where(string $key, mixed $operator = null, mixed $value = null): static
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        
        return $this->filter($this->operatorForWhere($key, $operator, $value));
    }
} 