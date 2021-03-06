<?php

return [
    'key' => env('LUS_KEY'), // Key that will be used to hash mnemonic key. Its one-way encrypt please do not lose this key. Once set do not change!
    'algo' => env('LUS_ALGO', 'sha256'), // Hash algo to use. Default to sha256.
    'mnemonic' => [
        'word_count' => env('LUS_MNEMONIC_WORD_COUNT', 12), // Generate mnemonic of 12, 15, 18, 21 or 24 words
    ],
];
