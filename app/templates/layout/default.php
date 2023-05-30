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
                        <div class="flex items-center md:order-2">
                            <?php if ($this->Identity->isLoggedIn()) : ?>
                                <?= $this->Html->link('Unsync', ['controller' => 'users', 'action' => 'logout']); ?>
                            <?php else : ?>
                                <div class="">
                                    <div x-data="beacon" class="">
                                        <button @click="login('<?= $this->request->getAttribute('csrfToken') ?>')">Sync</button>
                                        <span x-show="error" class="text-red-300 font-semibold"><span x-text="error"></span></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="mobile-menu-2">
                            <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 md:flex-row md:space-x-8 md:mt-0 md:border-0">
                                <li>
                                    <?= $this->Html->link('Airdrops', ['controller' => 'Airdrops', 'action' => 'index']); ?>
                                </li>
                                <li>
                                    <?= $this->Html->link('Tokens', ['controller' => 'Tokens', 'action' => 'index']); ?>
                                </li>
                                <li>
                                    <?= $this->Html->link('Recipients', ['controller' => 'Recipients', 'action' => 'index']); ?>
                                </li>
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
