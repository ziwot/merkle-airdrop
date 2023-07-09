<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Airdrop> $airdrops
 */
?>
<div class="airdrops index content">
    <?= $this->Html->link(__('New Airdrop'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Airdrops') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('token_id') ?></th>
                    <th><?= $this->Paginator->sort('merkle_root') ?></th>
                    <th><?= $this->Paginator->sort('address') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($airdrops as $airdrop): ?>
                <tr>
                    <td><?= $this->Number->format($airdrop->id) ?></td>
                    <td><?= $airdrop->has('token') ? $this->Html->link($airdrop->token->id, ['controller' => 'Tokens', 'action' => 'view', $airdrop->token->id]) : '' ?></td>
                    <td><?= h($airdrop->merkle_root) ?></td>
                    <td><?= h($airdrop->address) ?></td>
                    <td><?= h($airdrop->name) ?></td>
                    <td><?= h($airdrop->created) ?></td>
                    <td><?= h($airdrop->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $airdrop->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $airdrop->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $airdrop->id], ['confirm' => __('Are you sure you want to delete # {0}?', $airdrop->id)]) ?>
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
