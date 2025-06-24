<?php

declare(strict_types=1);

namespace App\Tezos\Airdrop;

enum ClaimStatus
{
    case Claimed;
    case Unclaimed;
    case Ineligible;
}
