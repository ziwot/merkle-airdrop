<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AirdropsRecipient $airdropsRecipient
 * @var \Cake\Collection\CollectionInterface|string[] $airdrops
 * @var \Cake\Collection\CollectionInterface|string[] $recipients
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Airdrops Recipients'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="airdropsRecipients form content">
            <?= $this->Form->create($airdropsRecipient) ?>
            <fieldset>
                <legend><?= __('Add Airdrops Recipient') ?></legend>
                <?php
                    echo $this->Form->control('airdrop_id', ['options' => $airdrops]);
                    echo $this->Form->control('recipient_id', ['options' => $recipients]);
                    echo $this->Form->control('amount');
                    echo $this->Form->control('claimed');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
