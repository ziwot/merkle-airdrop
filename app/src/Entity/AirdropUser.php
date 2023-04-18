<?php

namespace App\Entity;

use App\Repository\AirdropUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AirdropUserRepository::class)]
class AirdropUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\ManyToOne(inversedBy: 'airdropUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Airdrop $airdrop = null;

    #[ORM\ManyToOne(inversedBy: 'airdropUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAirdrop(): ?Airdrop
    {
        return $this->airdrop;
    }

    public function setAirdrop(?Airdrop $airdrop): self
    {
        $this->airdrop = $airdrop;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
