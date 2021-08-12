<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("category:read")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("category:read")
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups("category:read")
     */
    private $discription;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="category", orphanRemoval=true)
     *  @Groups("category:read")
     */
    private $posts;
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return Collection|Comment[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDiscription(): ?string
    {
        return $this->discription;
    }

    public function setDiscription(string $discription): self
    {
        $this->discription = $discription;

        return $this;
    }
}
