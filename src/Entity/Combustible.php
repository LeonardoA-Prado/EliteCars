<?php

namespace App\Entity;

use App\Repository\CombustibleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CombustibleRepository::class)]
class Combustible
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Coche>
     */
    #[ORM\OneToMany(targetEntity: Coche::class, mappedBy: 'Combustible')]
    private Collection $coches;

    public function __construct()
    {
        $this->coches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Coche>
     */
    public function getCoches(): Collection
    {
        return $this->coches;
    }

    public function addCoch(Coche $coch): static
    {
        if (!$this->coches->contains($coch)) {
            $this->coches->add($coch);
            $coch->setCombustible($this);
        }

        return $this;
    }

    public function removeCoch(Coche $coch): static
    {
        if ($this->coches->removeElement($coch)) {
            // set the owning side to null (unless already changed)
            if ($coch->getCombustible() === $this) {
                $coch->setCombustible(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre;
    }
}
