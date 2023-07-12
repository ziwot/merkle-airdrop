<?php

namespace App\Tezos;

enum Network: string
{
    case MAINNET = 'mainnet';
    case GHOSTNET = 'ghostnet';
    case LOCAL = 'local';

    public function rpc_url(): string
    {
        return match ($this) {
            Network::MAINNET => 'https://rpc.tzbeta.net',
            Network::GHOSTNET => 'https://ghostnet.tezos.marigold.dev',
            Network::LOCAL => 'http://localhost:20000',
        };
    }

    public function tzkt_url():string {
        return match ($this) {
            Network::MAINNET => 'https://api.tzkt.io',
            Network::GHOSTNET => 'https://api.ghostnet.tzkt.io',
            Network::LOCAL => 'http://localhost:5000',
        };
    }
}
