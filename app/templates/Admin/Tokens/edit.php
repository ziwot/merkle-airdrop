<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Token $token
 */

$this->append('script', $this->Html->script('app/tokens/edit'));
?>

<div class="mb-3 d-flex justify-content-between">
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

    <?= $this->element('CakeTezos.get_metadata', [
            'address' => $token->address,
            'callBackUrl' => $this->Url->build([
                '_name' => 'admin:tokens:edit',
                $token->id
            ]),
            'csrfToken' => $this->request->getAttribute('csrfToken'),
            'successHandler' => 'handleMetadataSuccess',
            'errorHandler' => 'handleMetadataError',
    ]) ?>
</div>

<div class="row">
    <div class="w-50">
        <?= $this->Form->create($token) ?>
        <fieldset>
            <legend><?= __('Edit Token') ?></legend>
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
    <div class="w-50">
        <pre class="metadata">
<?= json_encode($token->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
        </pre>
    </div>
</div>
