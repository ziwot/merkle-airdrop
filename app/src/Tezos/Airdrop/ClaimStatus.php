<?php

declare(strict_types = 1);

namespace App\Tezos\Airdrop;

enum ClaimStatus {
	case CLAIMED;
	case UNCLAIMED;
	case INELIGIBLE;
}
