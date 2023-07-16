<?php

namespace App\Entity;

use App\Repository\RecipientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipientRepository::class)]
class Recipient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 36)]
    private ?string $address = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated = null;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: AirdropRecipient::class, orphanRemoval: true)]
    private Collection $airdropRecipients;

    public function __construct()
    {
        $this->airdropRecipients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): static
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return Collection<int, AirdropRecipient>
     */
    public function getAirdropRecipients(): Collection
    {
        return $this->airdropRecipients;
    }

    public function addAirdropRecipient(AirdropRecipient $airdropRecipient): static
    {
        if (!$this->airdropRecipients->contains($airdropRecipient)) {
            $this->airdropRecipients->add($airdropRecipient);
            $airdropRecipient->setRecipient($this);
        }

        return $this;
    }

    public function removeAirdropRecipient(AirdropRecipient $airdropRecipient): static
    {
        if ($this->airdropRecipients->removeElement($airdropRecipient)) {
            // set the owning side to null (unless already changed)
            if ($airdropRecipient->getRecipient() === $this) {
                $airdropRecipient->setRecipient(null);
            }
        }

        return $this;
    }
}
