<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\AirdropsUser> $airdropsUsers
 */
?>
<div class="airdropsUsers index content">
    <?= $this->Html->link(__('New Airdrops User'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Airdrops Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('airdrop_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('amount') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($airdropsUsers as $airdropsUser): ?>
                <tr>
                    <td><?= $airdropsUser->has('airdrop') ? $this->Html->link($airdropsUser->airdrop->name, ['controller' => 'Airdrops', 'action' => 'view', $airdropsUser->airdrop->id]) : '' ?></td>
                    <td><?= $airdropsUser->has('user') ? $this->Html->link($airdropsUser->user->id, ['controller' => 'Users', 'action' => 'view', $airdropsUser->user->id]) : '' ?></td>
                    <td><?= $this->Number->format($airdropsUser->amount) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $airdropsUser->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $airdropsUser->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $airdropsUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $airdropsUser->id)]) ?>
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
