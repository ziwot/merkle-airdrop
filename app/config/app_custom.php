<?php

return [
    'CakeTezos' => [
        'defaultNetwork' => 'mainnet',

        'networks' => [
            'mainnet' => [
                'rpcUrl' => 'https://rpc.tzbeta.net',
                'tzktUrl' => 'https://api.tzkt.io',
                'networkId' => 'NetXdQprcVkpaWU',
                'label' => 'Mainnet',
            ],
            'shadownet' => [
                'rpcUrl' => 'https://rpc.shadownet.teztnets.com',
                'tzktUrl' => 'https://api.shadownet.tzkt.io',
                'networkId' => 'NetXsqzbfFenSTS',
                'label' => 'Shadownet',
            ],
            'local' => [
                'rpcUrl' => 'http://localhost:8732',
                'tzktUrl' => 'http://localhost:5000',
                'networkId' => 'NetXtJqPyJGB6Pc',
                'label' => 'Local',
            ],
        ],

        'redirect' => [
            'afterLogin' => '/',
            'afterLogout' => ['_name' => 'homepage'],
        ],

        'siwt' => [
            'statement' => 'I accept the Terms of Service',
        ],

        'cache' => [
            'balance' => [
                'enabled' => true,
                'config' => 'default',
            ],
        ],
    ],

    'IdeHelper' => [
        'genericsInParam' => true,
        'arrayAsGenerics' => true,
        'concreteEntitiesInParam' => 'strict',
    ],
];
