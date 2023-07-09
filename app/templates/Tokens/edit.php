<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Token $token
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $token->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $token->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Tokens'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tokens form content">
            <?= $this->Form->create($token) ?>
            <fieldset>
                <legend><?= __('Edit Token') ?></legend>
                <?php
                    echo $this->Form->control('network');
                    echo $this->Form->control('address');
                    echo $this->Form->control('identifier');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
