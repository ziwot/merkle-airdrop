<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 24)]
    private ?string $network = null;

    #[ORM\Column(length: 36)]
    private ?string $address = null;

    #[ORM\Column]
    private ?int $identifier = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated = null;

    #[ORM\OneToMany(mappedBy: 'token', targetEntity: Airdrop::class, orphanRemoval: true)]
    private Collection $airdrops;

    public function __construct()
    {
        $this->airdrops = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNetwork(): ?string
    {
        return $this->network;
    }

    public function setNetwork(string $network): static
    {
        $this->network = $network;

        return $this;
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

    public function getIdentifier(): ?int
    {
        return $this->identifier;
    }

    public function setIdentifier(int $identifier): static
    {
        $this->identifier = $identifier;

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
     * @return Collection<int, Airdrop>
     */
    public function getAirdrops(): Collection
    {
        return $this->airdrops;
    }

    public function addAirdrop(Airdrop $airdrop): static
    {
        if (!$this->airdrops->contains($airdrop)) {
            $this->airdrops->add($airdrop);
            $airdrop->setToken($this);
        }

        return $this;
    }

    public function removeAirdrop(Airdrop $airdrop): static
    {
        if ($this->airdrops->removeElement($airdrop)) {
            // set the owning side to null (unless already changed)
            if ($airdrop->getToken() === $this) {
                $airdrop->setToken(null);
            }
        }

        return $this;
    }
}
