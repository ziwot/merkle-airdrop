<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AirdropsRecipient $airdropsRecipient
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Airdrops Recipient'), ['action' => 'edit', $airdropsRecipient->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Airdrops Recipient'), ['action' => 'delete', $airdropsRecipient->id], ['confirm' => __('Are you sure you want to delete # {0}?', $airdropsRecipient->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Airdrops Recipients'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Airdrops Recipient'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="airdropsRecipients view content">
            <h3><?= h($airdropsRecipient->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Airdrop') ?></th>
                    <td><?= $airdropsRecipient->has('airdrop') ? $this->Html->link($airdropsRecipient->airdrop->name, ['controller' => 'Airdrops', 'action' => 'view', $airdropsRecipient->airdrop->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Recipient') ?></th>
                    <td><?= $airdropsRecipient->has('recipient') ? $this->Html->link($airdropsRecipient->recipient->id, ['controller' => 'Recipients', 'action' => 'view', $airdropsRecipient->recipient->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($airdropsRecipient->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount') ?></th>
                    <td><?= $this->Number->format($airdropsRecipient->amount) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
