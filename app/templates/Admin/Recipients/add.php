<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Recipient $recipient
 * @var \Cake\Collection\CollectionInterface|array<string> $airdrops
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(
                __('List Recipients'),
                ['action' => 'index'],
                ['class' => 'side-nav-item'],
            ) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="recipients form content">
            <?= $this->Form->create($recipient) ?>
            <fieldset>
                <legend><?= __('Add Recipient') ?></legend>
                <?php
                echo $this->Form->control('address');
                echo $this->Form->control('airdrops._ids', [
                    'options' => $airdrops,
                ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
