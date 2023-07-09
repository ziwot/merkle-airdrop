<?php

namespace App\View\Helper;

use Cake\View\Helper;

class TzHelper extends Helper
{
    public function shortenAddress(string $address): string
    {
        return substr($address, 0, 6) . '...' . substr($address, strlen($address) - 6);
    }
}
