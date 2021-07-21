<?php

return [
    'supervisor-1' => [
        'connection' => 'redis',
        'queue' => [
            'default',
            'suppress-leads',
        ],
        'balance' => 'auto',
        'processes' => 5,
        'tries' => 1,
    ],
    'outbound-dispatchers' => [
        'connection' => 'redis-long-running',
        'queue' => [
            'process-pending-outbound-replies',
            'dispatch-account-jobs',
            'process-account',
        ],
        'balance' => 'auto',
        'processes' => 5,
        'tries' => 1,
        'timeout' => 300,
    ],
    'campaigns' => [
        'connection' => 'redis-long-running',
        'queue' => [
            'generate-messages',
        ],
        'balance' => 'auto',
        'processes' => 2,
        'tries' => 1,
        'timeout' => 3000,
    ],
    'campaign-creation' => [
        'connection' => 'redis',
        'queue' => [
            'create-outbound-from-lead',
        ],
        'balance' => 'auto',
        'processes' => 35,
        'tries' => 2,
        'timeout' => 60,
    ],
    'outbounds' => [
        'connection' => 'redis',
        'queue' => [
            'process-pending-outbound',
        ],
        'balance' => 'auto',
        'processes' => 45,
        'tries' => 1,
    ],
    'numbers' => [
        'connection' => 'redis',
        'queue' => [
            'import-numbers',
        ],
        'balance' => 'simple',
        'processes' => 2,
        'tries' => 1,
    ],
    'process-file-uploads' => [
        'connection' => 'redis',
        'queue' => [
            'process-file-uploads',
        ],
        'balance' => 'simple',
        'processes' => 2,
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
        'processes' => 10,
        'tries' => 1,
        'timeout' => 120,
    ],
    'domains' => [
        'connection' => 'redis',
        'queue' => [
            'update-dns',
        ],
        'balance' => 'auto',
        'processes' => 5,
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
