<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Token $token
 */
?>
<div class="mb-3 d-flex">
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
</div>

<div class="row">
    <?= $this->Form->create($token) ?>
    <fieldset>
        <?php
		echo $this->Form->control('network');
		echo $this->Form->control('address');
		echo $this->Form->control('identifier');
		?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']) ?>
    <a class="btn btn-secondary" href="<?= $this->Url->build([
		'_name' => 'admin:tokens:index',
	]) ?>">Back</a>
    <?= $this->Form->end() ?>
</div>
