<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AirdropsRecipient Entity
 *
 * @property int $id
 * @property int $airdrop_id
 * @property int $recipient_id
 * @property int $amount
 *
 * @property \App\Model\Entity\Airdrop $airdrop
 * @property \App\Model\Entity\Recipient $recipient
 */
class AirdropsRecipient extends Entity
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
        'airdrop_id' => true,
        'recipient_id' => true,
        'amount' => true,
        'airdrop' => true,
        'recipient' => true,
    ];
}
