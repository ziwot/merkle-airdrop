<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$description = 'Merkle Airdrop';
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $description ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->AssetMix->script('app') ?>
    <?= $this->AssetMix->css('app') ?>

    <?= $this->fetch('meta') ?>
</head>

<body>
    <div class="flex flex-col h-screen">
        <div class="flex-grow pb-8">
            <div class="bg-blue-400">
                <nav class="container mx-auto text-white">
                    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                        <?= $this->Html->link('Tezos Airdrops', '/', ['class' => 'self-center text-2xl font-semibold whitespace-nowrap']); ?>
                        <?php if ($this->Identity->isLoggedIn()) : ?>
                            <div x-data="profile" class="flex items-center md:order-2">
                                <button @click="$float({offset: 10}, {trap: true})" type="button" class="flex mr-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full" src="https://pbs.twimg.com/profile_images/1660739913631817728/WM93OmoG_reasonably_small.jpg" alt="user photo">
                                </button>
                                <div  x-ref="panel" class="panel z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                                    <div class="px-4 py-3">
                                        <span class="block text-sm text-gray-900 dark:text-white">Bonnie Green</span>
                                        <span class="block text-sm  text-gray-500 truncate dark:text-gray-400">name@flowbite.com</span>
                                    </div>
                                    <ul class="py-2" aria-labelledby="user-menu-button">
                                        <li>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Dashboard</a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Earnings</a>
                                        </li>
                                        <li>
                                            <?= $this->Html->link('Sign out', [
                                                'controller' => 'users',
                                                'action' => 'logout',
                                            ], [
                                                'class' => 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white'
                                            ]); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php else : ?>
                            <div x-data="beacon" class="flex items-center md:order-2">
                                <button @click="login('<?= $this->request->getAttribute('csrfToken') ?>')">Sync</button>
                                <span x-show="error" class="text-red-600 font-semibold"><span x-text="error"></span></span>
                            </div>
                        <?php endif; ?>
                        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="mobile-menu-2">
                            <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 md:flex-row md:space-x-8 md:mt-0 md:border-0">
                                <li>
                                    <?= $this->Html->link('Airdrops', ['controller' => 'Airdrops', 'action' => 'index']); ?>
                                </li>

                                <?php if ($this->Identity->isLoggedIn()) : ?>
                                    <li>
                                        <?= $this->Html->link('Airdrops Recipients', ['controller' => 'AirdropsRecipients', 'action' => 'index']); ?>
                                    </li>
                                    <li>
                                        <?= $this->Html->link('Tokens', ['controller' => 'Tokens', 'action' => 'index']); ?>
                                    </li>
                                    <li>
                                        <?= $this->Html->link('Recipients', ['controller' => 'Recipients', 'action' => 'index']); ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <main class="container mx-auto">
                <div class="container">
                    <?= $this->Flash->render() ?>
                    <?= $this->fetch('content') ?>
                </div>
            </main>
        </div>
        <footer class="bg-blue-500 text-white">
            <div class="container mx-auto">
                <div class="flex flex-row py-4">
                    https://github.com/der-alter/merkle-airdrop
                </div>
            </div>
        </footer>
    </div>
</body>
