<?php

namespace App\Entity;

use App\Repository\CochesImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CochesImagesRepository::class)]
class CochesImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rutaImagen = null;

    #[ORM\Column(nullable: true)]
    private ?int $posicion = null;

    #[ORM\ManyToOne(inversedBy: 'cochesImages')]
    private ?Coche $coche_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRutaImagen(): ?string
    {
        return $this->rutaImagen;
    }

    public function setRutaImagen(?string $rutaImagen): static
    {
        $this->rutaImagen = $rutaImagen;

        return $this;
    }

    public function getPosicion(): ?int
    {
        return $this->posicion;
    }

    public function setPosicion(?int $posicion): static
    {
        $this->posicion = $posicion;

        return $this;
    }

    public function getCocheId(): ?Coche
    {
        return $this->coche_id;
    }

    public function setCocheId(?Coche $coche_id): static
    {
        $this->coche_id = $coche_id;

        return $this;
    }
}
