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

    #[ORM\ManyToOne(inversedBy: 'coches')]
    private ?Usuario $vendedor = null;

    /**
     * @var Collection<int, CochesImages>
     */
    #[ORM\OneToMany(targetEntity: CochesImages::class, mappedBy: 'coche_id',cascade: ['persist','remove'], orphanRemoval: true)]
    private Collection $cochesImages;

    #[ORM\ManyToOne(inversedBy: 'coches')]
    private ?Marcas $marca = null;

    #[ORM\ManyToOne(inversedBy: 'coches')]
    private ?Combustible $Combustible = null;

    #[ORM\Column]
    private ?bool $vendido = null;


    public function __construct()
    {
        $this->transaccions = new ArrayCollection();
        $this->cochesImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVendedor(): ?Usuario
    {
        return $this->vendedor;
    }

    public function setVendedor(?Usuario $vendedor): static
    {
        $this->vendedor = $vendedor;

        return $this;
    }

    /**
     * @return Collection<int, CochesImages>
     */
    public function getCochesImages(): Collection
    {
        return $this->cochesImages;
    }

    public function addCochesImage(CochesImages $cochesImage): static
    {
        if (!$this->cochesImages->contains($cochesImage)) {
            $this->cochesImages->add($cochesImage);
            $cochesImage->setCocheId($this);
        }

        return $this;
    }

    public function removeCochesImage(CochesImages $cochesImage): static
    {
        if ($this->cochesImages->removeElement($cochesImage)) {
            // set the owning side to null (unless already changed)
            if ($cochesImage->getCocheId() === $this) {
                $cochesImage->setCocheId(null);
            }
        }

        return $this;
    }

    public function getMarca(): ?Marcas
    {
        return $this->marca;
    }

    public function setMarca(?Marcas $marca): static
    {
        $this->marca = $marca;

        return $this;
    }

    public function getCombustible(): ?Combustible
    {
        return $this->Combustible;
    }

    public function setCombustible(?Combustible $Combustible): static
    {
        $this->Combustible = $Combustible;

        return $this;
    }

    public function isVendido(): ?bool
    {
        return $this->vendido;
    }

    public function setVendido(bool $vendido): static
    {
        $this->vendido = $vendido;

        return $this;
    }

}
