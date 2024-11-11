<?php

declare(strict_types = 1);

return [
	'debug' => true,

	'Security' => [
		'salt' => env('SECURITY_SALT', '__SALT__'),
	],

	'Datasources' => [
		'default' => [
			'url' => 'mysql://my_app:secret@localhost:3307/my_app?persistent=false',
		],
	],
];
