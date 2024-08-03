<?php

namespace App\Tezos\Airdrop;

enum ClaimStatus {
	case CLAIMED;
	case UNCLAIMED;
	case INELIGIBLE;
}
