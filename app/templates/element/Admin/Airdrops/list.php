<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $q
 * @var \App\Model\Entity\Airdrop[]|\Cake\Collection\CollectionInterface $airdrops
 */
?>
<?php foreach ($airdrops as $airdrop): ?>
<tr>
    <th scope="row">
        <?= $airdrop->name ?>
    </th>
    <td><?= $airdrop->created ?></td>
    <td><?= $airdrop->modified ?></td>
    <td>
        <a class="text-decoration-none p-1" href="<?= $this->Url->build([
			'_name' => 'admin:airdrops:edit',
			$airdrop->id,
		]) ?>">
            <?= $this->Html->icon('pencil-square') ?>
        </a>
        <a class="text-decoration-none p-1" href="<?= $this->Url->build([
			'_name' => 'admin:airdrops:view',
			$airdrop->id,
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
					'_name' => 'admin:airdrops:index',
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
<?php endif;
