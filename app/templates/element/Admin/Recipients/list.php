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
        <?php echo $recipient->address ?>
    </th>
    <td><?= $recipient->created ?></td>
    <td><?= $recipient->modified ?></td>
    <td>
        <a class="text-decoration-none p-1" href="<?= $this->Url->build(['_name' => 'admin:recipients:edit', $recipient->id]) ?>">
            <?= $this->Html->icon('pencil-square') ?>
        </a>
        <a class="text-decoration-none p-1" href="<?= $this->Url->build(['_name' => 'admin:recipients:view', $recipient->id]) ?>">
            <?= $this->Html->icon('eye-fill') ?>
        </a>
    </td>
</tr>
<?php endforeach; ?>
<?php if ($this->Paginator->hasNext()) : ?>
<tr>
    <td colspan="4">
        <div>
            <span
                hx-get="<?php echo $this->Url->build(
                    [
                    'action' => 'index',
                    '?' => [
                        'page' => $this->Paginator->current() + 1,
                        'q' => $q,
                    ],
                    ]
                        ) ?>"
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

