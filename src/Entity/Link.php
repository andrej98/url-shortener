<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=LinkRepository::class)
 * @UniqueEntity("url_key")
 */
class Link
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $original;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortened;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url_key;

    /**
     * @ORM\Column(type="integer")
     */
    private $visits = 0;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="links")
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginal(): ?string
    {
        return $this->original;
    }

    public function setOriginal(string $original): self
    {
        $this->original = $original;

        return $this;
    }

    public function getShortened(): ?string
    {
        return $this->shortened;
    }

    public function setShortened(string $shortened): self
    {
        $this->shortened = $shortened;

        return $this;
    }

    public function getUrlKey(): ?string
    {
        return $this->url_key;
    }

    public function setUrlKey(string $url_key): self
    {
        $this->url_key = $url_key;

        return $this;
    }

    public function getVisits(): ?int
    {
        return $this->visits;
    }

    public function setVisits(int $visits): self
    {
        $this->visits = $visits;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
    
}