<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La catégorie saisie {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères',
    )]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Program::class)]
    private $programs;

    public function getId(): ?int
    {
        return $this->id;
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

    public function __construct()
{
    $this->programs = new ArrayCollection();
}

public function getPrograms(): Collection
{
    return $this->programs;
}

public function addProgram(Program $program): self
{
    if (!$this->programs->contains($program)) {
        $this->programs->add($program);
        $program->setCategory($this);
    }

    return $this;
}

public function removeProgram(Program $program): self
{
    if ($this->programs->removeElement($program)) {
        // set the owning side to null (unless already changed)
        if ($program->getCategory() === $this) {
            $program->setCategory(null);
        }
    }

    return $this;
}
}
