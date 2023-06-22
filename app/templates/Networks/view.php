<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Network $network
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Network'), ['action' => 'edit', $network->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Network'), ['action' => 'delete', $network->id], ['confirm' => __('Are you sure you want to delete # {0}?', $network->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Networks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Network'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="networks view content">
            <h3><?= h($network->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($network->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Base Url') ?></th>
                    <td><?= h($network->base_url) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($network->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
