<?php

use Scriptmancer\Support\HtmlElement;

require_once __DIR__ . '/../../vendor/autoload.php';

// Example 1: Basic link
$link = HtmlElement::tag('a')
    ->addAttribute('href', 'https://example.com')
    ->addClass(['btn', 'btn-primary'])
    ->text('Visit Example');
echo $link . "\n\n";

// Example 2: Nested elements
$card = HtmlElement::tag('div')
    ->addClass('card')
    ->addChild(
        HtmlElement::tag('h2')->addClass('card-title')->text('Card Title')
    )
    ->addChild(
        HtmlElement::tag('p')->addClass('card-body')->text('This is a card body.')
    );
echo $card . "\n\n";

// Example 3: Manipulation
$list = HtmlElement::tag('ul')->addClass('list');
$list->addChild(HtmlElement::tag('li')->text('Item 1'));
$list->addChild(HtmlElement::tag('li')->text('Item 2'));
$list->addChild(HtmlElement::tag('li')->text('Item 3'));
$list->removeChild(1); // Remove 'Item 2'
echo $list . "\n\n";

// Example 4: fromHtml
$parsed = HtmlElement::fromHtml('<div class="foo"><span>Hello</span><span>World</span></div>');
echo $parsed ? $parsed->toHtml() : 'Parse failed';
