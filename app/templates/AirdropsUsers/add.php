<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AirdropsUser $airdropsUser
 * @var \Cake\Collection\CollectionInterface|string[] $airdrops
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Airdrops Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="airdropsUsers form content">
            <?= $this->Form->create($airdropsUser) ?>
            <fieldset>
                <legend><?= __('Add Airdrops User') ?></legend>
                <?php
                    echo $this->Form->control('airdrop_id', ['options' => $airdrops]);
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('amount');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
