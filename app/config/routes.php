<?php

declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->prefix('Admin', ['_namePrefix' => 'admin:'], function (
        RouteBuilder $builder,
    ): void {
        $builder->scope(
            '/recipients',
            ['_namePrefix' => 'recipients:'],
            function (RouteBuilder $builder): void {
                $builder->connect('/', 'Admin/Recipients::index', [
                    '_name' => 'index',
                ]);
                $builder->connect('/add', 'Admin/Recipients::add', [
                    '_name' => 'add',
                ]);
                $builder->connect('/delete/*', 'Admin/Recipients::delete', [
                    '_name' => 'delete',
                ]);
                $builder->connect('/edit/*', 'Admin/Recipients::edit', [
                    '_name' => 'edit',
                ]);
                $builder->connect('/view/*', 'Admin/Recipients::view', [
                    '_name' => 'view',
                ]);
            },
        );

        $builder->scope(
            '/airdrops',
            ['_namePrefix' => 'airdrops:'],
            function (RouteBuilder $builder): void {
                $builder->connect('/', 'Admin/Airdrops::index', [
                    '_name' => 'index',
                ]);
                $builder->connect('/add', 'Admin/Airdrops::add', [
                    '_name' => 'add',
                ]);
                $builder->connect('/delete/*', 'Admin/Airdrops::delete', [
                    '_name' => 'delete',
                ]);
                $builder->connect('/edit/*', 'Admin/Airdrops::edit', [
                    '_name' => 'edit',
                ]);
                $builder->connect('/view/*', 'Admin/Airdrops::view', [
                    '_name' => 'view',
                ]);
            },
        );

        $builder->scope(
            '/tokens',
            ['_namePrefix' => 'tokens:'],
            function (RouteBuilder $builder): void {
                $builder->connect('/', 'Admin/Tokens::index', [
                    '_name' => 'index',
                ]);
                $builder->connect('/add', 'Admin/Tokens::add', [
                    '_name' => 'add',
                ]);
                $builder->connect('/delete/*', 'Admin/Tokens::delete', [
                    '_name' => 'delete',
                ]);
                $builder->connect('/edit/*', 'Admin/Tokens::edit', [
                    '_name' => 'edit',
                ]);
                $builder->connect('/view/*', 'Admin/Tokens::view', [
                    '_name' => 'view',
                ]);
            },
        );
    });

    $routes->scope('/', function (RouteBuilder $builder): void {
        $builder->connect('/', ['controller' => 'Homepage'], ['_name' => 'homepage']);
        $builder->connect('/pages/*', 'Pages::display');

        $builder->fallbacks();
    });
};
