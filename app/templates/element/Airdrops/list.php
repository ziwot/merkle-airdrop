<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $q
 * @var \Cake\Collection\CollectionInterface|array<\App\Model\Entity\Airdrop> $airdrops
 */
?>
<?php foreach ($airdrops as $airdrop) : ?>
<tr>
    <th scope="row">
        <?= $airdrop->name ?>
    </th>
    <td><?= $airdrop->created ?></td>
    <td><?= $airdrop->modified ?></td>
    <td>
        <?= $this->Html->link('Edit', [
            'action' => 'edit',
            $airdrop->id,
        ]) ?>
        <?= $this->Html->link('View', [
            'action' => 'view',
            $airdrop->id,
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


