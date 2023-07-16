<?php

namespace App\Entity;

use App\Repository\AirdropRecipientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AirdropRecipientRepository::class)]
class AirdropRecipient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $claimed = null;

    #[ORM\ManyToOne(inversedBy: 'airdropRecipients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipient $recipient = null;

    #[ORM\ManyToOne(inversedBy: 'airdropRecipients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Airdrop $airdrop = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getClaimed(): ?\DateTimeInterface
    {
        return $this->claimed;
    }

    public function setClaimed(?\DateTimeInterface $claimed): static
    {
        $this->claimed = $claimed;

        return $this;
    }

    public function getRecipient(): ?Recipient
    {
        return $this->recipient;
    }

    public function setRecipient(?Recipient $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getAirdrop(): ?Airdrop
    {
        return $this->airdrop;
    }

    public function setAirdrop(?Airdrop $airdrop): static
    {
        $this->airdrop = $airdrop;

        return $this;
    }
}
