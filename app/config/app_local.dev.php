<?php

declare(strict_types=1);

return [
    'debug' => true,

    'Security' => [
        'salt' => env('SECURITY_SALT', '__SALT__'),
    ],

    'Datasources' => [
        'default' => [
            'url' => 'mysql://root:secret@localhost/airdrop?persistent=false',
        ],
    ],
];
