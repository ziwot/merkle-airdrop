<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Network $network
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $network->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $network->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Networks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="networks form content">
            <?= $this->Form->create($network) ?>
            <fieldset>
                <legend><?= __('Edit Network') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('base_url');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
