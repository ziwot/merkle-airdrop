<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Token $token
 */
?>
<div class="d-flex mb-3 gap-2">
    <?= $this->Html->link(
		__('Edit Token'),
		['_name' => 'admin:tokens:edit', $token->id],
		['class' => 'btn btn-primary'],
	) ?>
    <?= $this->Form->postLink(
		__('Delete'),
		['_name' => 'admin:tokens:delete', $token->id],
		[
			'method' => 'delete',
			'confirm' => __(
				'Are you sure you want to delete {0}?',
				$token->address,
			),
			'class' => 'btn btn-danger',
		],
	) ?>
    <a class="btn btn-secondary" href="<?= $this->Url->build([
		'_name' => 'admin:tokens:index',
	]) ?>">Back</a>
</div>

<div class="row">
    <table class="table table-striped">
        <tr>
            <th><?= __('Network') ?></th>
            <td><?= h($token->network) ?></td>
        </tr>
        <tr>
            <th><?= __('Address') ?></th>
            <td><?= h($token->address) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($token->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Identifier') ?></th>
            <td><?= $this->Number->format($token->identifier) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($token->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($token->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Airdrops') ?></h4>
        <?php if (!empty($token->airdrops)): ?>
        <div class="table-responsive">
            <table>
                <tr>
                    <th><?= __('Id') ?></th>
                    <th><?= __('Token Id') ?></th>
                    <th><?= __('Merkle Root') ?></th>
                    <th><?= __('Address') ?></th>
                    <th><?= __('Name') ?></th>
                    <th><?= __('Description') ?></th>
                    <th><?= __('Created') ?></th>
                    <th><?= __('Modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                <?php foreach ($token->airdrops as $airdrops): ?>
                <tr>
                    <td><?= h($airdrops->id) ?></td>
                    <td><?= h($airdrops->token_id) ?></td>
                    <td><?= h($airdrops->merkle_root) ?></td>
                    <td><?= h($airdrops->address) ?></td>
                    <td><?= h($airdrops->name) ?></td>
                    <td><?= h($airdrops->description) ?></td>
                    <td><?= h($airdrops->created) ?></td>
                    <td><?= h($airdrops->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), [
							'controller' => 'Airdrops',
							'action' => 'view',
							$airdrops->id,
						]) ?>
                        <?= $this->Html->link(__('Edit'), [
							'controller' => 'Airdrops',
							'action' => 'edit',
							$airdrops->id,
						]) ?>
                        <?= $this->Form->postLink(
							__('Delete'),
							[
								'controller' => 'Airdrops',
								'action' => 'delete',
								$airdrops->id,
							],
							[
								'confirm' => __(
									'Are you sure you want to delete # {0}?',
									$airdrops->id,
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
