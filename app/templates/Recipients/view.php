<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Recipient $recipient
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Recipient'), ['action' => 'edit', $recipient->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Recipient'), ['action' => 'delete', $recipient->id], ['confirm' => __('Are you sure you want to delete # {0}?', $recipient->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Recipients'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Recipient'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="recipients view content">
            <h3><?= h($recipient->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Address') ?></th>
                    <td><?= h($recipient->address) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($recipient->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($recipient->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($recipient->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Airdrops') ?></h4>
                <?php if (!empty($recipient->airdrops)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Token Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($recipient->airdrops as $airdrops) : ?>
                        <tr>
                            <td><?= h($airdrops->id) ?></td>
                            <td><?= h($airdrops->token_id) ?></td>
                            <td><?= h($airdrops->name) ?></td>
                            <td><?= h($airdrops->description) ?></td>
                            <td><?= h($airdrops->created) ?></td>
                            <td><?= h($airdrops->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Airdrops', 'action' => 'view', $airdrops->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Airdrops', 'action' => 'edit', $airdrops->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Airdrops', 'action' => 'delete', $airdrops->id], ['confirm' => __('Are you sure you want to delete # {0}?', $airdrops->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
