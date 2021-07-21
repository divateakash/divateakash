<?php

return [
    'supervisor-1' => [
        'connection' => 'redis',
        'queue' => [
            'default',
            'suppress-leads',
        ],
        'balance' => 'auto',
        'processes' => 10,
        'tries' => 1,
    ],
    'outbound-dispatchers' => [
        'connection' => 'redis',
        'queue' => [
            'process-pending-outbound-replies',
            'dispatch-account-jobs',
            'process-account',
        ],
        'balance' => 'auto',
        'processes' => 10,
        'tries' => 1,
        'timeout' => 200,
    ],
    'campaigns' => [
        'connection' => 'redis',
        'queue' => [
            'generate-messages',
            'generate-outbounds-from-leads',
        ],
        'balance' => 'auto',
        'processes' => 10,
        'tries' => 1,
        'timeout' => 1200,
    ],
    'outbounds' => [
        'connection' => 'redis',
        'queue' => [
            'process-pending-outbound',
        ],
        'balance' => 'simple',
        'processes' => 200,
        'tries' => 1,
    ],
    'numbers' => [
        'connection' => 'redis',
        'queue' => [
            'import-numbers',
        ],
        'balance' => 'simple',
        'processes' => 5,
        'tries' => 1,
    ],
    'process-file-uploads' => [
        'connection' => 'redis',
        'queue' => [
            'process-file-uploads',
        ],
        'balance' => 'simple',
        'processes' => 5,
        'tries' => 3,
        'timeout' => 3600,
    ],
    'process-fam-leads' => [
        'connection' => 'redis',
        'queue' => [
            'process-fam-leads',
        ],
        'balance' => 'simple',
        'processes' => 1,
        'tries' => 5,
        'timeout' => 7200,
    ],
    'convert-file-upload-record' => [
        'connection' => 'redis',
        'queue' => [
            'convert-file-upload-record',
            'move-to-repliers-list',
        ],
        'balance' => 'auto',
        'processes' => 50,
        'tries' => 1,
        'timeout' => 120,
    ],
    'domains' => [
        'connection' => 'redis',
        'queue' => [
            'update-dns',
        ],
        'balance' => 'auto',
        'processes' => 10,
        'tries' => 1,
        'timeout' => 60,
    ],
    'file-uploads' => [
        'connection' => 'redis',
        'queue' => [
            'increment-file-upload-breakdown',
        ],
        'balance' => 'auto',
        'processes' => 1,
        'tries' => 1,
        'timeout' => 60,
    ],
];
