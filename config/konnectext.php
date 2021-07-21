<?php

return [
    'domains_ip' => env('REDIRECT_IP'),
    'message_suffix' => env('MESSAGE_SUFFIX'),
    'max_chars' => env('MAX_CHARS', 170),
    'global_words' => env('GLOBAL_WORDS', true),
    '24h_limit' => env('24_LIMIT', false),
];
