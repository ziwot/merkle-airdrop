<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $q
 * @var \Cake\Collection\CollectionInterface|array<\App\Model\Entity\Token> $tokens
 */
?>
<?php foreach ($tokens as $token): ?>
<tr>
    <th scope="row">
        <?= $token->network ?>
    </th>
    <td><?= $token->address ?></td>
    <td><?= $this->Number->format($token->identifier) ?></td>
    <td>
        <a class="text-decoration-none p-1" href="<?= $this->Url->build([
            '_name' => 'admin:tokens:edit',
            $token->id,
        ]) ?>">
            <?= $this->Html->icon('pencil-square') ?>
        </a>
        <a class="text-decoration-none p-1" href="<?= $this->Url->build([
            '_name' => 'admin:tokens:view',
            $token->id,
        ]) ?>">
            <?= $this->Html->icon('eye-fill') ?>
        </a>
    </td>
</tr>
<?php endforeach; ?>
<?php if ($this->Paginator->hasNext()): ?>
<tr>
    <td colspan="4">
        <div>
            <span
                hx-get="<?= $this->Url->build([
                    '_name' => 'admin:tokens:index',
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

