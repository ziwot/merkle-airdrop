<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Recipient $recipient
 */
?>
<div class="d-flex mb-3 gap-2">
    <?= $this->Html->link(
		__('Edit Recipient'),
		['_name' => 'admin:recipients:edit', $recipient->id],
		['class' => 'btn btn-primary'],
	) ?>
    <?= $this->Form->postLink(
		__('Delete'),
		['_name' => 'admin:recipients:delete', $recipient->id],
		[
			'method' => 'delete',
			'confirm' => __(
				'Are you sure you want to delete {0}?',
				$recipient->address,
			),
			'class' => 'btn btn-danger',
		],
	) ?>
    <a class="btn btn-secondary" href="<?= $this->Url->build([
		'_name' => 'admin:recipients:index',
	]) ?>">Back</a>
</div>

<div class="row">
    <table class="table table-striped">
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
        <?php if (!empty($recipient->airdrops)): ?>
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
                <?php foreach ($recipient->airdrops as $airdrops): ?>
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
							'_name' => 'admin:airdrops:view',
							$airdrops->id,
						]) ?>
                        <?= $this->Html->link(__('Edit'), [
							'_name' => 'admin:airdrops:edit',
							$airdrops->id,
						]) ?>
                        <?= $this->Form->postLink(
							__('Delete'),
							['_name' => 'admin:airdrops:delete', $airdrops->id],
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
