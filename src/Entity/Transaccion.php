<?php

namespace App\Entity;

use App\Repository\TransaccionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransaccionRepository::class)]
class Transaccion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaTransaccion = null;

    #[ORM\Column]
    private ?float $precioTransaccion = null;

    #[ORM\ManyToOne(inversedBy: 'transaccions')]
    private ?Coche $coche = null;

    #[ORM\ManyToOne(inversedBy: 'transaccions')]
    private ?Usuario $comprador = null;

    #[ORM\ManyToOne(inversedBy: 'transaccions')]
    private ?Usuario $vendedor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaTransaccion(): ?\DateTimeInterface
    {
        return $this->fechaTransaccion;
    }

    public function setFechaTransaccion(\DateTimeInterface $fechaTransaccion): static
    {
        $this->fechaTransaccion = $fechaTransaccion;

        return $this;
    }

    public function getPrecioTransaccion(): ?float
    {
        return $this->precioTransaccion;
    }

    public function setPrecioTransaccion(float $precioTransaccion): static
    {
        $this->precioTransaccion = $precioTransaccion;

        return $this;
    }

    public function getCoche(): ?Coche
    {
        return $this->coche;
    }

    public function setCoche(?Coche $coche): static
    {
        $this->coche = $coche;

        return $this;
    }

    public function getComprador(): ?Usuario
    {
        return $this->comprador;
    }

    public function setComprador(?Usuario $comprador): static
    {
        $this->comprador = $comprador;

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
}
