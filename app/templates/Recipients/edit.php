<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Recipient $recipient
 * @var string[]|\Cake\Collection\CollectionInterface $airdrops
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $recipient->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $recipient->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Recipients'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="recipients form content">
            <?= $this->Form->create($recipient) ?>
            <fieldset>
                <legend><?= __('Edit Recipient') ?></legend>
                <?php
                    echo $this->Form->control('address');
                    echo $this->Form->control('airdrops._ids', ['options' => $airdrops]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
