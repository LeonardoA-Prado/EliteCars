<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $apellidos = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $contrasena = null;

    /**
     * @var Collection<int, Transaccion>
     */
    #[ORM\OneToMany(targetEntity: Transaccion::class, mappedBy: 'comprador')]
    private Collection $transaccions;

    public function __construct()
    {
        $this->transaccions = new ArrayCollection();
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

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): static
    {
        $this->contrasena = $contrasena;

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
            $transaccion->setComprador($this);
        }

        return $this;
    }

    public function removeTransaccion(Transaccion $transaccion): static
    {
        if ($this->transaccions->removeElement($transaccion)) {
            // set the owning side to null (unless already changed)
            if ($transaccion->getComprador() === $this) {
                $transaccion->setComprador(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        // Devuelve un array de roles. Por defecto, asignamos el rol "ROLE_USER".
        return ['ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return $this->contrasena;
    }

    public function getSalt(): ?string
    {
        // No se necesita un salt si usas algoritmos modernos como bcrypt o sodium.
        return null;
    }

    public function getUsername(): string
    {
        // Symfony usa este método para identificar al usuario. Usaremos el email como identificador.
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        // Symfony usa este método para identificar al usuario. Usaremos el email como identificador.
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // Si almacenas datos sensibles en la entidad, límpialos aquí.
    }
}
