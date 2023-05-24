<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\AirdropsRecipient> $airdropsRecipients
 */
?>
<div class="airdropsRecipients index content">
    <?= $this->Html->link(__('New Airdrops Recipient'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Airdrops Recipients') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('airdrop_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('amount') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($airdropsRecipients as $airdropsRecipient): ?>
                <tr>
                    <td><?= $this->Number->format($airdropsRecipient->id) ?></td>
                    <td><?= $airdropsRecipient->has('airdrop') ? $this->Html->link($airdropsRecipient->airdrop->name, ['controller' => 'Airdrops', 'action' => 'view', $airdropsRecipient->airdrop->id]) : '' ?></td>
                    <td><?= $airdropsRecipient->has('recipient') ? $this->Html->link($airdropsRecipient->recipient->id, ['controller' => 'Recipients', 'action' => 'view', $airdropsRecipient->recipient->id]) : '' ?></td>
                    <td><?= $this->Number->format($airdropsRecipient->amount) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $airdropsRecipient->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $airdropsRecipient->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $airdropsRecipient->id], ['confirm' => __('Are you sure you want to delete # {0}?', $airdropsRecipient->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
