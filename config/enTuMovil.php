<?php

return [
    'user' => env('BLUEEYE_USER', 'user'),
    'pass' => env('BLUEEYE_PASS', 'password'),
    'url' => env('BLUEEYE_SEND_URL', 'http://10.176.6.22:8080/blueeye-http/sendMsg'),
    'keyword' => env('BLUEYE_KEYWORD', 'keyword'),
    'hasKeyword' => env('BLUEEYE_HASKEYWORD', true),
    'smscId' => env('BLUEEYE_SMSCID', 'cubacel'),
];
