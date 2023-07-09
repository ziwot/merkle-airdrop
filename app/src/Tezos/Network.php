<?php

namespace App\Tezos;

enum Network: string
{
    case MAINNET = 'mainnet';
    case GHOSTNET = 'ghostnet';
    case LOCAL = 'local';

    public function base_url(): string
    {
        return match ($this) {
            Network::MAINNET => 'https://rpc.tzbeta.net',
            Network::GHOSTNET => 'https://ghostnet.tezos.marigold.dev',
            Network::LOCAL => 'http://localhost:20000',
        };
    }
}
