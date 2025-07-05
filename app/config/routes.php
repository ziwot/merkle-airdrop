<?php

declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    /*
     * The default class to use for all routes
     *
     * The following route classes are supplied with CakePHP and are appropriate
     * to set as the default:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * If no call is made to `Router::defaultRouteClass()`, the class used is
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Note that `Route` does not do any inflections on URLs which will result in
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
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

    $routes->scope('/users', ['_namePrefix' => 'users:'], function (RouteBuilder $builder): void {
        $builder->connect('/login', 'Users::login', [
            '_name' => 'login',
        ]);

        $builder->connect('/logout', 'Users::logout', [
            '_name' => 'logout',
        ]);
    });

    $routes->scope('/', function (RouteBuilder $builder): void {
        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        $builder->connect('/', ['controller' => 'Homepage'], ['_name' => 'homepage']);

        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        $builder->connect('/pages/*', 'Pages::display');

        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * You can remove these routes once you've connected the
         * routes you want in your application.
         */
        $builder->fallbacks();
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder) {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */

    $routes->post(
        '/signin',
        ['controller' => 'Users', 'action' => 'login'],
        'signin',
    );
};
