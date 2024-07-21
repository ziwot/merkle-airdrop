<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $q
 * @var \Cake\Collection\CollectionInterface|array<\App\Model\Entity\Recipient> $recipients
 */
?>
<?php foreach ($recipients as $recipient) : ?>
<tr>
    <th scope="row">
        <?= $recipient->address ?>
    </th>
    <td><?= $recipient->created ?></td>
    <td><?= $recipient->modified ?></td>
    <td>
        <?= $this->Html->link('Edit', [
            'action' => 'edit',
            $recipient->id,
        ]) ?>
        <?= $this->Html->link('View', [
            'action' => 'view',
            $recipient->id,
        ]) ?>
    </td>
</tr>
<?php endforeach; ?>
<?php if ($this->Paginator->hasNext()) : ?>
<tr>
    <td colspan="4">
        <div>
            <span
                hx-get="<?= $this->Url->build([
                    'action' => 'index',
                    '?' => [
                        'page' => $this->Paginator->current() + 1,
                        'q' => $q,
                    ],
                ]) ?>"
                hx-trigger="revealed"
                hx-select="tr"
                hx-target="closest tr"
                hx-swap="outerHTML"
            >
            </span>
        </div>
    </td>
</tr>
<?php endif; ?>

