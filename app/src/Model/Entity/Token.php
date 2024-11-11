<?php

declare(strict_types = 1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Token Entity
 *
 * @property int $id
 * @property string $network
 * @property string $address
 * @property int $identifier
 * @property string $metadata
 * @property string $token_metadata
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Airdrop[] $airdrops
 */
class Token extends Entity {

	/**
	 * Fields that can be mass assigned using newEntity() or patchEntity().
	 *
	 * Note that when '*' is set to true, this allows all unspecified fields to
	 * be mass assigned. For security purposes, it is advised to set '*' to false
	 * (or remove it), and explicitly make individual fields accessible as needed.
	 *
	 * @var array<string, bool>
	 */
	protected array $_accessible = [
		'network' => true,
		'address' => true,
		'identifier' => true,
		'metadata' => true,
		'token_metadata' => true,
		'created' => true,
		'modified' => true,
		'airdrops' => true,
	];

}
