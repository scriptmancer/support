<?php

namespace Nazim\Support;

use Closure;

class Pipeline
{
    /**
     * Create a new class instance.
     *
     * @param  mixed  $passable  The object being passed through the pipeline.
     * @param  array  $pipes     The array of class pipes.
     * @param  string  $method   The method to call on each pipe.
     */
    public function __construct(
        protected mixed $passable = null,
        protected array $pipes = [],
        protected string $method = 'handle'
    ) {
    }

    /**
     * Set the object being sent through the pipeline.
     *
     * @param  mixed  $passable
     * @return $this
     */
    public function send(mixed $passable): self
    {
        $this->passable = $passable;

        return $this;
    }

    /**
     * Set the array of pipes.
     *
     * @param  array|mixed  $pipes
     * @return $this
     */
    public function through(array|string ...$pipes): self
    {
        $this->pipes = is_array($pipes[0]) ? $pipes[0] : $pipes;

        return $this;
    }

    /**
     * Set the method to call on the pipes.
     *
     * @param  string  $method
     * @return $this
     */
    public function via(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Run the pipeline with a final destination callback.
     *
     * @param  \Closure  $destination
     * @return mixed
     */
    public function then(Closure $destination): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            $this->carry(),
            $this->prepareDestination($destination)
        );

        return $pipeline($this->passable);
    }

    /**
     * Run the pipeline and return the result.
     *
     * @return mixed
     */
    public function thenReturn(): mixed
    {
        return $this->then(fn ($passable) => $passable);
    }

    /**
     * Get the final piece of the Closure onion.
     *
     * @param  \Closure  $destination
     * @return \Closure
     */
    protected function prepareDestination(Closure $destination): Closure
    {
        return fn ($passable) => $destination($passable);
    }

    /**
     * Get a Closure that represents a slice of the application onion.
     *
     * @return \Closure
     */
    protected function carry(): Closure
    {
        return function ($stack, $pipe): Closure {
            return function ($passable) use ($stack, $pipe) {
                return match (true) {
                    is_callable($pipe) => $pipe($passable, $stack),
                    is_object($pipe) => $pipe->{$this->method}($passable, $stack),
                    is_string($pipe) => (new $pipe())->{$this->method}($passable, $stack),
                    default => throw new \InvalidArgumentException('Invalid pipe type')
                };
            };
        };
    }

    /**
     * Create a new pipeline with the given passable.
     *
     * @param  mixed  $passable
     * @return static
     */
    public static function make(mixed $passable = null): self
    {
        return new static($passable);
    }
} 