<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Recipient> $recipients
 */
?>
<div class="recipients index content">
    <?= $this->Html->link(__('New Recipient'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Recipients') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('address') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recipients as $recipient): ?>
                <tr>
                    <td><?= $this->Number->format($recipient->id) ?></td>
                    <td><?= h($recipient->address) ?></td>
                    <td><?= h($recipient->created) ?></td>
                    <td><?= h($recipient->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $recipient->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $recipient->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $recipient->id], ['confirm' => __('Are you sure you want to delete # {0}?', $recipient->id)]) ?>
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
