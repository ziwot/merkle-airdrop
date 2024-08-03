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
            <?= $this->Html->link(
                __('Edit Airdrop'),
                ['action' => 'edit', $airdrop->id],
                ['class' => 'side-nav-item'],
            ) ?>
            <?= $this->Form->postLink(
                __('Delete Airdrop'),
                ['action' => 'delete', $airdrop->id],
                [
                    'method' => 'delete',
                    'confirm' => __(
                        'Are you sure you want to delete # {0}?',
                        $airdrop->id,
                    ),
                    'class' => 'side-nav-item',
                ],
            ) ?>
            <?= $this->Html->link(
                __('List Airdrops'),
                ['action' => 'index'],
                ['class' => 'side-nav-item'],
            ) ?>
            <?= $this->Html->link(
                __('New Airdrop'),
                ['action' => 'add'],
                ['class' => 'side-nav-item'],
            ) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="airdrops view content">
            <h3><?= h($airdrop->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Token') ?></th>
                    <td><?= $airdrop->has('token')
                        ? $this->Html->link($airdrop->token->id, [
                            'controller' => 'Tokens',
                            'action' => 'view',
                            $airdrop->token->id,
                        ])
                        : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Merkle Root') ?></th>
                    <td><?= h($airdrop->merkle_root) ?></td>
                </tr>
                <tr>
                    <th><?= __('Address') ?></th>
                    <td><?= h($airdrop->address) ?></td>
                </tr>
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
                    <?= $this->Text->autoParagraph(h($airdrop->description)) ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Recipients') ?></h4>
                <?php if (!empty($airdrop->recipients)): ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Address') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($airdrop->recipients as $recipients): ?>
                        <tr>
                            <td><?= h($recipients->id) ?></td>
                            <td><?= h($recipients->address) ?></td>
                            <td><?= h($recipients->created) ?></td>
                            <td><?= h($recipients->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), [
                                    'controller' => 'Recipients',
                                    'action' => 'view',
                                    $recipients->id,
                                ]) ?>
                                <?= $this->Html->link(__('Edit'), [
                                    'controller' => 'Recipients',
                                    'action' => 'edit',
                                    $recipients->id,
                                ]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    [
                                        'controller' => 'Recipients',
                                        'action' => 'delete',
                                        $recipients->id,
                                    ],
                                    [
                                        'confirm' => __(
                                            'Are you sure you want to delete # {0}?',
                                            $recipients->id,
                                        ),
                                    ],
                                ) ?>
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
