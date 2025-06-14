<?php

namespace App\Entity;

use App\Repository\CocheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CocheRepository::class)]
class Coche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $marca = null;

    #[ORM\Column(length: 255)]
    private ?string $modelo = null;

    #[ORM\Column(length: 255)]
    private ?string $version = null;

    #[ORM\Column]
    private ?float $precio = null;

    #[ORM\Column]
    private ?int $kilometros = null;

    #[ORM\Column(length: 255)]
    private ?string $ciudad = null;

    #[ORM\Column(length: 255)]
    private ?string $carroceria = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $cambio = null;

    #[ORM\Column(length: 255)]
    private ?string $combustible = null;

    #[ORM\Column(length: 255)]
    private ?string $traccion = null;

    #[ORM\Column]
    private ?int $potencia = null;

    #[ORM\Column]
    private ?int $cilindrada = null;

    /**
     * @var Collection<int, Transaccion>
     */
    #[ORM\OneToMany(targetEntity: Transaccion::class, mappedBy: 'coche')]
    private Collection $transaccions;

    public function __construct()
    {
        $this->transaccions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(string $marca): static
    {
        $this->marca = $marca;

        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(string $modelo): static
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getKilometros(): ?int
    {
        return $this->kilometros;
    }

    public function setKilometros(int $kilometros): static
    {
        $this->kilometros = $kilometros;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(string $ciudad): static
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getCarroceria(): ?string
    {
        return $this->carroceria;
    }

    public function setCarroceria(string $carroceria): static
    {
        $this->carroceria = $carroceria;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getCambio(): ?string
    {
        return $this->cambio;
    }

    public function setCambio(string $cambio): static
    {
        $this->cambio = $cambio;

        return $this;
    }

    public function getCombustible(): ?string
    {
        return $this->combustible;
    }

    public function setCombustible(string $combustible): static
    {
        $this->combustible = $combustible;

        return $this;
    }

    public function getTraccion(): ?string
    {
        return $this->traccion;
    }

    public function setTraccion(string $traccion): static
    {
        $this->traccion = $traccion;

        return $this;
    }

    public function getPotencia(): ?int
    {
        return $this->potencia;
    }

    public function setPotencia(int $potencia): static
    {
        $this->potencia = $potencia;

        return $this;
    }

    public function getCilindrada(): ?int
    {
        return $this->cilindrada;
    }

    public function setCilindrada(int $cilindrada): static
    {
        $this->cilindrada = $cilindrada;

        return $this;
    }

    /**
     * @return Collection<int, Transaccion>
     */
    public function getTransaccions(): Collection
    {
        return $this->transaccions;
    }

    public function addTransaccion(Transaccion $transaccion): static
    {
        if (!$this->transaccions->contains($transaccion)) {
            $this->transaccions->add($transaccion);
            $transaccion->setCoche($this);
        }

        return $this;
    }

    public function removeTransaccion(Transaccion $transaccion): static
    {
        if ($this->transaccions->removeElement($transaccion)) {
            // set the owning side to null (unless already changed)
            if ($transaccion->getCoche() === $this) {
                $transaccion->setCoche(null);
            }
        }

        return $this;
    }
}
