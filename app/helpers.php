<?php

function team()
{
    return auth()->user()->team;
}

function number($number)
{
    $number = preg_replace('/[^0-9]/', '', $number);

    return '1' . substr($number, -10);
}

function e164($number)
{
    $number = preg_replace('/[^0-9]/', '', $number);

    return '+' . number($number);
}

function local_number($number)
{
    $number = preg_replace('/[^0-9]/', '', $number);

    return substr($number, -10);
}

function logo_url()
{
    $host = request()->getHost();
    $path = "images/{$host}_logo.png";

    if (file_exists(public_path($path))) {
        return '/' . $path;
    }

    return '/images/logo.png';
}

function app_name()
{
    $host = request()->getHost();

    return [
        'smsportal.cc' => 'SMS Portal',
    ][$host] ?? config('app.name');
}

function get_hours()
{
    return collect(range(0, 23))
        ->map(fn($hour) => $hour < 10 ? "0{$hour}" : $hour)
        ->flatMap(fn($hour) => ["{$hour}:00", "{$hour}:30"])
        ->all();
}

function percentage($value, $total)
{
    $diff = $total - ($total - $value);

    $total = $total ?: 1;

    return ($diff / ($total)) * 100;
}

function bad_words()
{
    return ['stop', 'quit', 'hault', 'unsub', 'exit', 'remove', 'cancel', 'off', 'no', 'not', 'spam', 'don\'t', 'fuck', 'wrong', 'suck', 'dick', 'ass', 'delete', 'govern', 'idiot', 'fuck', 'stupid', 'leave', 'end'];
}
