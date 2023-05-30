<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Airdrop Entity
 *
 * @property int $id
 * @property int $token_id
 * @property string $name
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Token $token
 * @property \App\Model\Entity\Recipient[] $recipients
 */
class Airdrop extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'token_id' => true,
        'name' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'token' => true,
        'recipients' => true,
    ];
}
