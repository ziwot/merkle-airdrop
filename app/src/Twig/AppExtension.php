<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('shortenAddress', [$this, 'shortenAddress']),
        ];
    }

    public function shortenAddress(string $address): string
    {
        return substr($address, 0, 6) . '...' . substr($address, strlen($address) - 6);
    }
}
