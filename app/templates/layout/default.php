<?php
/**
 * @var \App\View\AppView $this
 */

$this->extend('base');
$this->append('title', ' – Tez Drops');
$this->append('script', $this->Html->importmap([
    'CakeTezos' => '/cake_tezos/dist/cake-tezos.js'
]));
?>

<header>
    <nav class="navbar" style="background-color: #e3f2fd;">
        <div class="container position-relative">
            <a class="navbar-brand" href="/">Tez Drops</a>
            <?php if ($this->Identity->isLoggedIn()) : ?>
            <ul class="navbar nav mt-5">
                <li class="nav-item">
                    <?= $this->Html->link(
                        'Airdrops',
                        ['_name' => 'admin:airdrops:index'],
                        ['class' => 'nav-link'],
                    ) ?>
                </li>
                <li class="nav-item">
                    <?= $this->Html->link(
                        'Tokens',
                        ['_name' => 'admin:tokens:index'],
                        ['class' => 'nav-link'],
                    ) ?>
                </li>
                <li class="nav-item">
                    <?= $this->Html->link(
                        'Recipients',
                        ['_name' => 'admin:recipients:index'],
                        ['class' => 'nav-link'],
                    ) ?>
                </li>
            </ul>
            <?php endif; ?>
                <div class="d-flex position-absolute top-0 end-0">
                    <?= $this->element('CakeTezos.connect') ?>
                    <div>
                        <?= $this->element('CakeTezos.network_selector') ?>
                    </div>
                </div>
        </div>
    </nav>
</header>

<main class="flex-shrink-0">
    <div class="container py-3">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </div>
</main>
