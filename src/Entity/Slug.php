<?php

namespace App\Entity;

use App\Repository\SlugRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SlugRepository::class)]
class Slug
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
