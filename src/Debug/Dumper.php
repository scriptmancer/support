<?php

namespace Nazim\Support\Debug;

use Kint\Kint;

class Dumper
{
    /**
     * Dump a value with Kint.
     *
     * @param  mixed  $value
     * @return void
     */
    public function dump($value)
    {
        if (class_exists(Kint::class)) {
            Kint::dump($value);
            return;
        }
        
        var_dump($value);
    }
} 