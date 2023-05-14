<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AirdropsUser $airdropsUser
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Airdrops User'), ['action' => 'edit', $airdropsUser->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Airdrops User'), ['action' => 'delete', $airdropsUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $airdropsUser->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Airdrops Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Airdrops User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="airdropsUsers view content">
            <h3><?= h($airdropsUser->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Airdrop') ?></th>
                    <td><?= $airdropsUser->has('airdrop') ? $this->Html->link($airdropsUser->airdrop->name, ['controller' => 'Airdrops', 'action' => 'view', $airdropsUser->airdrop->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $airdropsUser->has('user') ? $this->Html->link($airdropsUser->user->id, ['controller' => 'Users', 'action' => 'view', $airdropsUser->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount') ?></th>
                    <td><?= $this->Number->format($airdropsUser->amount) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
