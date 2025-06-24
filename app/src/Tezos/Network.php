<?php

declare(strict_types=1);

namespace App\Tezos;

/**
 * Stores defaults Tezos & Tzkt nodes
 */
enum Network: string
{
    case Mainnet = 'mainnet';
    case Ghostnet = 'ghostnet';
    case Local = 'local';

    /**
     * @return string
     */
    public function rpcUrl(): string
    {
        return match ($this) {
            Network::MAINNET => 'https://rpc.tzbeta.net',
            Network::GHOSTNET => 'https://ghostnet.tezos.marigold.dev',
            Network::LOCAL => 'http://localhost:20000',
        };
    }

    /**
     * @return string
     */
    public function tzktUrl(): string
    {
        return match ($this) {
            Network::MAINNET => 'https://api.tzkt.io',
            Network::GHOSTNET => 'https://api.ghostnet.tzkt.io',
            Network::LOCAL => 'http://localhost:5000',
        };
    }
}
