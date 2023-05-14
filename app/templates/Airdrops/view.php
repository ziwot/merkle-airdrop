<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Airdrop $airdrop
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Airdrop'), ['action' => 'edit', $airdrop->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Airdrop'), ['action' => 'delete', $airdrop->id], ['confirm' => __('Are you sure you want to delete # {0}?', $airdrop->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Airdrops'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Airdrop'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="airdrops view content">
            <h3><?= h($airdrop->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($airdrop->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($airdrop->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($airdrop->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($airdrop->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($airdrop->description)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
