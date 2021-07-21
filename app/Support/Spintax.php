<?php

namespace App\Support;

class Spintax
{
    public static function parse($text)
    {
        return (new static )->process($text);
    }

    public static function longest($text)
    {
        return (new static )->process($text, 'long');
    }

    public function process($text, $method = 'replace')
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*?)\}/x',
            [$this, $method],
            $text
        );
    }

    public function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);

        return $parts[array_rand($parts)];
    }

    public function long($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);

        return collect($parts)->sortByDesc(fn($part) => strlen($part))->first();
    }
}
