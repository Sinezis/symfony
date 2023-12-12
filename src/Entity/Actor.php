<?php

namespace App\Entity;

use App\Repository\ActorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use DateTime;
use DatetimeInterface;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[Vich\Uploadable] 
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Program::class, inversedBy: 'actors')]
    private Collection $programs;

    #[ORM\Column(type: 'string', length: 255, nullable:true)]
    private ?string $picture;

    #[Vich\UploadableField(mapping: 'actor_file', fileNameProperty: 'picture')]
     private ?File $pictureFile = null;

     #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
     private ?DatetimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Program>
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): static
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
        }

        return $this;
    }

    public function removeProgram(Program $program): static
    {
        $this->programs->removeElement($program);

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function setPictureFile(File $image = null): Actor
    {
        $this->pictureFile = $image;
        if ($image) {
          $this->updatedAt = new DateTime('now');
        }
    
        return $this;
      }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    public function getUpdatedAt(): ?DatetimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DatetimeInterface $updatedAt): Program
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
