<?php

declare(strict_types=1);

return [
    'Security' => [
        'salt' => '__SALT__',
    ],

    'ViteHelper.forceProductionMode' => true,

    'Datasources' => [
        'default' => [
            'url' => 'mysql://__DB_USER__:__DB_PASS__@__DB_USER__.mysql.db/__DB_USER__?persistent=false',
        ],
    ],
];
