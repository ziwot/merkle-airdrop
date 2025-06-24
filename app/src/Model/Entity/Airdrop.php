<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Airdrop Entity
 *
 * @property int $id
 * @property int $token_id
 * @property string|null $merkle_root
 * @property string|null $address
 * @property string $name
 * @property string|null $description
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Token $token
 * @property array<\App\Model\Entity\Recipient> $recipients
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
    protected array $_accessible = [
        'token_id' => true,
        'merkle_root' => true,
        'address' => true,
        'name' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'token' => true,
        'recipients' => true,
    ];
}
