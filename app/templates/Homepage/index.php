<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Airdrop[] $recentAirdrops
 */
?>
<div class="recent-airdrops">
    <h2>Recent Airdrops</h2>
    <?php foreach ($recentAirdrops as $airdrop) : ?>
        <div class="airdrop">
            <h3><?= $airdrop->name ?><h3>
                    <h4><?= $airdrop->token->address ?></h4>
                    <p><?= $airdrop->description ?></p>
        </div>
        <?php if ($this->Identity->isLoggedIn()) : ?>
            <button>Check Eligibility</button>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
