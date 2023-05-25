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
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build('/') ?>">Homepage</a>
        </div>
        <div class="top-nav-links">
            <?php if ($this->Identity->isLoggedIn()) : ?>
                <?= $this->Html->link('Airdrops', ['controller' => 'Airdrops', 'action' => 'index']); ?>
                <?= $this->Html->link('Tokens', ['controller' => 'Tokens', 'action' => 'index']); ?>
                <?= $this->Html->link('Recipients', ['controller' => 'Recipients', 'action' => 'index']); ?>
                <?= $this->Html->link('Disconnect', ['controller' => 'users', 'action' => 'logout']); ?>
            <?php else : ?>
                <?= $this->Html->link('Connect', ['controller' => '', 'action' => '']); ?>
            <?php endif; ?>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
            <div data-controller="hello">
                <input data-hello-target="name" type="text">

                <button data-action="click->hello#greet">Greet</button>

                <span data-hello-target="output"></span>
            </div>
        </div>
    </main>
    <footer>
    </footer>
</body>

</html>
