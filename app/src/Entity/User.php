<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 36)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AirdropUser::class, orphanRemoval: true)]
    private Collection $airdropUsers;

    public function __construct()
    {
        $this->airdropUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, AirdropUser>
     */
    public function getAirdropUsers(): Collection
    {
        return $this->airdropUsers;
    }

    public function addAirdropUser(AirdropUser $airdropUser): self
    {
        if (!$this->airdropUsers->contains($airdropUser)) {
            $this->airdropUsers->add($airdropUser);
            $airdropUser->setUser($this);
        }

        return $this;
    }

    public function removeAirdropUser(AirdropUser $airdropUser): self
    {
        if ($this->airdropUsers->removeElement($airdropUser)) {
            // set the owning side to null (unless already changed)
            if ($airdropUser->getUser() === $this) {
                $airdropUser->setUser(null);
            }
        }

        return $this;
    }
}
