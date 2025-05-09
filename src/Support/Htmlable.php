<?php

namespace Scriptmancer\Support;

/**
 * Contract for HTML renderable objects.
 */
interface Htmlable
{
    /**
     * Get HTML representation of the object.
     * @return string
     */
    public function toHtml(): string;
}
