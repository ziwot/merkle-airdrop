<?php
/**
 * @var \App\View\AppView $this
 */

$this->extend('base');
$this->append('title', ' – Tez Drops');
?>

<header>
    <nav class="navbar" style="background-color: #e3f2fd;">
        <div class="container">
            <a class="navbar-brand" href="/">Tez Drops</a>
            <?php if ($this->Identity->isLoggedIn()) : ?>
            <ul class="navbar nav">
                <li class="nav-item">
                    <?php echo $this->Html->link(
                        'Airdrops',
                        ['_name' => 'admin:airdrops:index'],
                        ['class' => 'nav-link'],
                    ) ?>
                </li>
                <li class="nav-item">
                    <?php echo $this->Html->link(
                        'Tokens',
                        ['_name' => 'admin:tokens:index'],
                        ['class' => 'nav-link'],
                    ) ?>
                </li>
                <li class="nav-item">
                    <?php echo $this->Html->link(
                        'Recipients',
                        ['_name' => 'admin:recipients:index'],
                        ['class' => 'nav-link'],
                    ) ?>
                </li>
            </ul>
            <?php endif; ?>
            <div class="d-flex md:order-2">
                <?php echo $this->element('CakeTezos.connect') ?>
                <div>
                    <?php echo $this->element('CakeTezos.network_selector') ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="flex-shrink-0">
    <div class="container py-3">
        <?php echo $this->Flash->render(); ?>
        <?php echo $this->fetch('content'); ?>
    </div>
</main>
