<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Recipient $recipient
 * @var \Cake\Collection\CollectionInterface|array<string> $airdrops
 */
?>
<div class="mb-3 d-flex">
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
</div>
<div class="row">
    <?= $this->Form->create($recipient) ?>
    <fieldset>
        <?php
        echo $this->Form->control('address');
        echo $this->Form->control('airdrops._ids', [
            'options' => $airdrops,
        ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), [
        'class' => 'btn btn-primary',
    ]) ?>
    <a class="btn btn-secondary" href="<?= $this->Url->build([
        '_name' => 'admin:recipients:index',
    ]) ?>">Back</a>
    <?= $this->Form->end() ?>
</div>
