<?php
/**
 * @var \App\View\AppView $this
 */

use Cake\I18n\Time;
use Cake\Routing\Router;

$this->extend('base');
$this->append('title', ' – Tez Drops');
?>

<script type="importmap">
    {
        "imports": {
            "CakeTezos": "/cake_tezos/dist/cake-tezos.js?v=ff"
        }
    }
</script>

<script type="module">
    import {
        connect
    } from "CakeTezos";

    const connectBtn = document.getElementById("connect");
    connectBtn?.addEventListener(
        "click",
        () => connect(
            "<?= Router::fullBaseUrl() ?>/cake-tezos",
            "<?= $this->request->getAttribute('csrfToken') ?>",
            "NetXdQprcVkpaWU",
            "<?= $statement ?? 'I accept the Terms of Service' ?>",
            "<?= random_int(1, 100000000) ?>",
            "<?= Time::now()->format(DateTimeImmutable::ATOM) ?>",
        )
    );
</script>

<header>
    <nav class="navbar" style="background-color: #e3f2fd;">
        <div class="container">
            <a class="navbar-brand" href="/">Tez Drops</a>
            <?php if ($this->Identity->isLoggedIn()): ?>
            <ul class="navbar nav">
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
            <div class="d-flex flex-column items-center md:order-2">
                <?php if ($this->Identity->isLoggedIn()) : ?>
                    Welcome, <?= $this->Identity->get('address') ?>
                    <?= $this->Html->link(
                        $this->Html->icon('power'),
                        ['plugin' => 'CakeTezos', 'controller' => 'Users', 'action' => 'logout'],
                        [
                            'class' => 'btn',
                            'escape' => false,
                        ],
                    ) ?>
                    <div class="align-self-end">
                            <?= $this->cell(
								'Balance',
								[],
								[
									'cache' => [
										'config' => 'short',
										'key' =>
											'inbox_' .
											$this->Identity->get('address'),
									],
								],
							) ?>
                        (<?= $this->request
							->getSession()
							->read('network', 'local') ?>)
                    </div>
                <?php else : ?>
                    <button id="connect" class="btn">
                        <?= $this->Html->icon('key-fill') ?>
                    </button>
                <?php endif; ?>
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
