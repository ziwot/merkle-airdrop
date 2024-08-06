<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Airdrop $airdrop
 * @var \Cake\Collection\CollectionInterface|array<string> $tokens
 * @var \Cake\Collection\CollectionInterface|array<string> $recipients
 */
?>
<div class="mb-3 d-flex">
    <?= $this->Form->postLink(
		__('Delete'),
		['_name' => 'admin:airdrops:delete', $airdrop->id],
		[
			'method' => 'delete',
			'confirm' => __(
				'Are you sure you want to delete {0}?',
				$airdrop->name,
			),
			'class' => 'btn btn-danger',
		],
	) ?>
</div>

<div class="row">
    <?= $this->Form->create($airdrop) ?>
    <fieldset>
        <legend><?= __('Edit Airdrop') ?></legend>
        <?php
		echo $this->Form->control('token_id', ['options' => $tokens]);
		echo $this->Form->control('merkle_root');
		echo $this->Form->control('address');
		echo $this->Form->control('name');
		echo $this->Form->control('description');
		echo $this->Form->control('recipients._ids', [
			'options' => $recipients,
		]);
		?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <a class="btn btn-secondary" href="<?= $this->Url->build([
		'_name' => 'admin:airdrops:index',
	]) ?>">Back</a>
    <?= $this->Form->end() ?>
</div>
