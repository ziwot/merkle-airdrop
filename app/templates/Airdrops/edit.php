<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Airdrop $airdrop
 * @var string[]|\Cake\Collection\CollectionInterface $tokens
 * @var string[]|\Cake\Collection\CollectionInterface $recipients
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $airdrop->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $airdrop->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Airdrops'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="airdrops form content">
            <?= $this->Form->create($airdrop) ?>
            <fieldset>
                <legend><?= __('Edit Airdrop') ?></legend>
                <?php
                    echo $this->Form->control('token_id', ['options' => $tokens]);
                    echo $this->Form->control('merkle_root');
                    echo $this->Form->control('address');
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
