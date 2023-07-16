<?php

namespace App\Entity;

use App\Repository\AirdropRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AirdropRepository::class)]
class Airdrop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $merkleRoot = null;

    #[ORM\Column(length: 36, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated = null;

    #[ORM\ManyToOne(inversedBy: 'airdrops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Token $token = null;

    #[ORM\OneToMany(mappedBy: 'airdrop', targetEntity: AirdropRecipient::class, orphanRemoval: true)]
    private Collection $airdropRecipients;

    public function __construct()
    {
        $this->airdropRecipients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMerkleRoot(): ?string
    {
        return $this->merkleRoot;
    }

    public function setMerkleRoot(?string $merkleRoot): static
    {
        $this->merkleRoot = $merkleRoot;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getToken(): ?Token
    {
        return $this->token;
    }

    public function setToken(?Token $token): static
    {
        $this->token = $token;

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
            $airdropRecipient->setAirdrop($this);
        }

        return $this;
    }

    public function removeAirdropRecipient(AirdropRecipient $airdropRecipient): static
    {
        if ($this->airdropRecipients->removeElement($airdropRecipient)) {
            // set the owning side to null (unless already changed)
            if ($airdropRecipient->getAirdrop() === $this) {
                $airdropRecipient->setAirdrop(null);
            }
        }

        return $this;
    }
}
