<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $q
 * @var \Cake\Collection\CollectionInterface|array<\App\Model\Entity\Token> $tokens
 */
?>
<?php foreach ($tokens as $token) : ?>
<tr>
    <th scope="row">
        <?= $token->network ?>
    </th>
    <td><?= $token->address ?></td>
    <td><?= $token->Identifier ?></td>
    <td>
        <?= $this->Html->link('Edit', [ 'action' => 'edit', $token->id ]) ?>
        <?= $this->Html->link('View', [ 'action' => 'view', $token->id ]) ?>
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

