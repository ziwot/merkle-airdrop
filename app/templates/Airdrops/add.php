<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Airdrop $airdrop
 * @var \Cake\Collection\CollectionInterface|string[] $tokens
 * @var \Cake\Collection\CollectionInterface|string[] $recipients
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Airdrops'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="airdrops form content">
            <?= $this->Form->create($airdrop) ?>
            <fieldset>
                <legend><?= __('Add Airdrop') ?></legend>
                <?php
                    echo $this->Form->control('token_id', ['options' => $tokens]);
                    echo $this->Form->control('name');
                    echo $this->Form->control('description');
                    echo $this->Form->control('recipients._ids', ['options' => $recipients]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
