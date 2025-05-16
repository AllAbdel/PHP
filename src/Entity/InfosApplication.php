<?php

namespace App\Entity;

use App\Repository\InfosApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfosApplicationRepository::class)]
class InfosApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Contrat>
     */
    #[ORM\OneToMany(targetEntity: Contrat::class, mappedBy: 'Application')]
    private Collection $contrats;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
{
    return $this->createdAt;
}

public function setCreatedAt(\DateTimeImmutable $createdAt): self
{
    $this->createdAt = $createdAt;
    return $this;
}

/**
 * @return Collection<int, Contrat>
 */
public function getContrats(): Collection
{
    return $this->contrats;
}

public function addContrat(Contrat $contrat): static
{
    if (!$this->contrats->contains($contrat)) {
        $this->contrats->add($contrat);
        $contrat->setApplication($this);
    }

    return $this;
}

public function removeContrat(Contrat $contrat): static
{
    if ($this->contrats->removeElement($contrat)) {
        // set the owning side to null (unless already changed)
        if ($contrat->getApplication() === $this) {
            $contrat->setApplication(null);
        }
    }

    return $this;
}
}
