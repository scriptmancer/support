<?php

namespace Scriptmancer\Support;

/**
 * Fluent, chainable HTML element builder and manipulator.
 *
 * @package Scriptmancer\Support
 */
class HtmlElement implements Htmlable
{
    protected string $tag;
    protected array $attributes = [];
    protected array $classes = [];
    protected array $children = [];
    protected ?string $text = null;
    protected array $styles = []; // Inline styles

    /**
     * Create a new HtmlElement.
     *
     * @param string $tag
     */
    public function __construct(string $tag)
    {
        $this->tag = $tag;
    }

    // Getters
    /**
     * Get the tag name.
     * @return string
     */
    public function getTag(): string { return $this->tag; }
    /**
     * Get all attributes.
     * @return array
     */
    public function getAttributes(): array { return $this->attributes; }
    /**
     * Get all classes.
     * @return array
     */
    public function getClasses(): array { return $this->classes; }
    /**
     * Get all children elements.
     * @return HtmlElement[]
     */
    public function getChildren(): array { return $this->children; }
    /**
     * Get text content (if any).
     * @return string|null
     */
    public function getText(): ?string { return $this->text; }

    // Attribute manipulation
    /**
     * Check if attribute exists.
     * @param string $key
     * @return bool
     */
    public function hasAttribute(string $key): bool { return array_key_exists($key, $this->attributes); }
    /**
     * Get an attribute by key.
     * @param string $key
     * @return mixed
     */
    public function getAttribute(string $key): mixed { return $this->attributes[$key] ?? null; }
    /**
     * Remove an attribute by key.
     * @param string $key
     * @return $this
     */
    public function removeAttribute(string $key): self { unset($this->attributes[$key]); return $this; }

    // Class manipulation
    /**
     * Check if a class exists.
     * @param string $class
     * @return bool
     */
    public function hasClass(string $class): bool { return in_array($class, $this->classes, true); }
    /**
     * Remove a class.
     * @param string $class
     * @return $this
     */
    public function removeClass(string $class): self { $this->classes = array_values(array_diff($this->classes, [$class])); return $this; }
    /**
     * Remove all classes.
     * @return $this
     */
    public function clearClasses(): self { $this->classes = []; return $this; }

    /**
     * Static factory for element.
     * @param string $tag
     * @return static
     */
    /**
     * Add or set a style property.
     * @param string $property
     * @param string $value
     * @return $this
     */
    public function addStyle(string $property, string $value): self
    {
        $this->styles[$property] = $value;
        return $this;
    }

    /**
     * Get a style property value.
     * @param string $property
     * @return string|null
     */
    public function getStyle(string $property): ?string
    {
        return $this->styles[$property] ?? null;
    }

    /**
     * Check if a style property exists.
     * @param string $property
     * @return bool
     */
    public function hasStyle(string $property): bool
    {
        return array_key_exists($property, $this->styles);
    }

    /**
     * Remove a style property.
     * @param string $property
     * @return $this
     */
    public function removeStyle(string $property): self
    {
        unset($this->styles[$property]);
        return $this;
    }

    /**
     * Remove all styles.
     * @return $this
     */
    public function clearStyles(): self
    {
        $this->styles = [];
        return $this;
    }

    /**
     * Get all styles as an array.
     * @return array
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * Add or set a data-* attribute.
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addData(string $key, $value): self
    {
        $this->attributes['data-' . $key] = $value;
        return $this;
    }

    /**
     * Get a data-* attribute.
     * @param string $key
     * @return mixed
     */
    public function getData(string $key): mixed
    {
        return $this->attributes['data-' . $key] ?? null;
    }

    /**
     * Check if a data-* attribute exists.
     * @param string $key
     * @return bool
     */
    public function hasData(string $key): bool
    {
        return array_key_exists('data-' . $key, $this->attributes);
    }

    /**
     * Remove a data-* attribute.
     * @param string $key
     * @return $this
     */
    public function removeData(string $key): self
    {
        unset($this->attributes['data-' . $key]);
        return $this;
    }

    /**
     * Add or set an aria-* attribute.
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addAria(string $key, $value): self
    {
        $this->attributes['aria-' . $key] = $value;
        return $this;
    }

    /**
     * Get an aria-* attribute.
     * @param string $key
     * @return mixed
     */
    public function getAria(string $key): mixed
    {
        return $this->attributes['aria-' . $key] ?? null;
    }

    /**
     * Check if an aria-* attribute exists.
     * @param string $key
     * @return bool
     */
    public function hasAria(string $key): bool
    {
        return array_key_exists('aria-' . $key, $this->attributes);
    }

    /**
     * Remove an aria-* attribute.
     * @param string $key
     * @return $this
     */
    public function removeAria(string $key): self
    {
        unset($this->attributes['aria-' . $key]);
        return $this;
    }

    public static function tag(string $tag): self
    {
        return new self($tag);
    }

    /**
     * Add or set an attribute.
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addAttribute(string $key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Add one or more classes.
     * @param string|array $class
     * @return $this
     */
    public function addClass($class): self
    {
        if (is_array($class)) {
            foreach ($class as $c) {
                $this->classes[] = $c;
            }
        } else {
            $this->classes[] = $class;
        }
        return $this;
    }

    /**
     * Add a child element.
     * @param HtmlElement $child
     * @return $this
     */
    /**
     * Add a child element.
     * @param HtmlElement $child
     * @return $this
     */
    public function addChild(self $child): self
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     * Set text content.
     * @param string $text
     * @return $this
     */
    public function text(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Render the element and its children as HTML.
     * @return string
     */
    public function toHtml(): string
    {
        $attrs = $this->attributes;
        if ($this->classes) {
            $attrs['class'] = trim(implode(' ', $this->classes));
        }
        if ($this->styles) {
            $styleString = '';
            foreach ($this->styles as $k => $v) {
                $styleString .= $k . ':' . $v . ';';
            }
            $attrs['style'] = trim($styleString);
        }
        $attrString = '';
        foreach ($attrs as $k => $v) {
            $attrString .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars((string)$v) . '"';
        }
        $html = "<{$this->tag}{$attrString}>";
        if ($this->text !== null) {
            $html .= htmlspecialchars($this->text);
        }
        foreach ($this->children as $child) {
            $html .= $child->toHtml();
        }
        $html .= "</{$this->tag}>";
        return $html;
    }

    /**
     * Magic string cast to HTML.
     * @return string
     */
    public function __toString(): string
    {
        return $this->toHtml();
    }

    /**
     * Parse a HTML string into an HtmlElement tree (basic implementation).
     * Only supports elements, attributes, text, and children.
     * @param string $html
     * @return HtmlElement|null
     */
    public static function fromHtml(string $html): ?self
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $root = $dom->getElementsByTagName('body')->item(0)->firstChild ?? $dom->documentElement;
        return self::fromDomNode($root);
    }

    /**
     * Recursively convert DOMNode to HtmlElement tree.
     * @param \DOMNode $node
     * @return HtmlElement|null
     */
    protected static function fromDomNode(\DOMNode $node): ?self
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            $el = self::tag('span');
            $el->text($node->nodeValue);
            return $el;
        }
        if ($node->nodeType !== XML_ELEMENT_NODE) return null;
        $el = self::tag($node->nodeName);
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                if ($attr->name === 'class') {
                    $el->addClass(explode(' ', $attr->value));
                } else {
                    $el->addAttribute($attr->name, $attr->value);
                }
            }
        }
        foreach ($node->childNodes as $child) {
            $childEl = self::fromDomNode($child);
            if ($childEl) {
                $el->addChild($childEl);
            }
        }
        return $el;
    }

    /**
     * Get a child by index.
     * @param int $index
     * @return HtmlElement|null
     */
    public function getChild(int $index): ?self
    {
        return $this->children[$index] ?? null;
    }

    /**
     * Replace a child at index.
     * @param int $index
     * @param HtmlElement $child
     * @return $this
     */
    public function setChild(int $index, self $child): self
    {
        if (isset($this->children[$index])) {
            $this->children[$index] = $child;
        }
        return $this;
    }

    /**
     * Replace all children.
     * @param HtmlElement[] $children
     * @return $this
     */
    public function setChildren(array $children): self
    {
        $this->children = $children;
        return $this;
    }

    /**
     * Add multiple children.
     * @param HtmlElement[] $children
     * @return $this
     */
    public function addChildren(array $children): self
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }
        return $this;
    }

    /**
     * Check if a child exists at index.
     * @param int $index
     * @return bool
     */
    public function hasChild(int $index): bool
    {
        return isset($this->children[$index]);
    }

    /**
     * Check if element has any children.
     * @return bool
     */
    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    /**
     * Remove a child by index.
     * @param int $index
     * @return $this
     */
    public function removeChild(int $index): self
    {
        if (isset($this->children[$index])) {
            array_splice($this->children, $index, 1);
        }
        return $this;
    }

    /**
     * Remove all children.
     * @return $this
     */
    public function clearChildren(): self
    {
        $this->children = [];
        return $this;
    }

    /**
     * Remove a child by instance.
     * @param HtmlElement $child
     * @return $this
     */
    public function removeChildInstance(self $child): self
    {
        $idx = $this->indexOfChild($child);
        if ($idx !== false) {
            $this->removeChild($idx);
        }
        return $this;
    }

    /**
     * Get the index of a child (by instance).
     * @param HtmlElement $child
     * @return int|false
     */
    public function indexOfChild(self $child): int|false
    {
        foreach ($this->children as $i => $c) {
            if ($c === $child) return $i;
        }
        return false;
    }

    /**
     * Apply a callback to all children.
     * @param callable $callback
     * @return $this
     */
    public function mapChildren(callable $callback): self
    {
        $this->children = array_map($callback, $this->children);
        return $this;
    }

    /**
     * Filter children by a callback.
     * @param callable $callback
     * @return $this
     */
    public function filterChildren(callable $callback): self
    {
        $this->children = array_values(array_filter($this->children, $callback));
        return $this;
    }

    /**
     * Remove children by tag name(s).
     * @param string|array $tags
     * @return $this
     */
    public function removeByTag($tags): self
    {
        $tags = (array) $tags;
        $this->children = array_filter($this->children, function($child) use ($tags) {
            return !in_array($child->getTag(), $tags, true);
        });
        $this->children = array_values($this->children);
        return $this;
    }
}
