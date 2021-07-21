<?php

namespace App\Support;

use Illuminate\Support\Str;

class BadWords
{
    public static function isPresent($words, $text)
    {
        $match = '';
        foreach ($words as $index => $word) {
            $match .= Str::lower($word);
            $match .= (($index + 1) == count($words)) ? '' : '|';
        }

        $regex = '/\b(' . $match . ')\b/i';

        return (bool) preg_match($regex, Str::lower($text));
    }
}
