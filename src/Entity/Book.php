<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $yearPublication = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ISBN = null;

    #[ORM\Column(nullable: true)]
    private ?int $count = null;

    #[ORM\ManyToMany(targetEntity: Author::class, mappedBy: 'books')]
    private Collection $authors;

    #[ORM\ManyToMany(targetEntity: Reader::class, inversedBy: 'books')]
    private Collection $readers;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: DiscardBook::class)]
    private Collection $discardBook;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->readers = new ArrayCollection();
        $this->discardBook = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getYearPublication(): ?string
    {
        return $this->yearPublication;
    }

    public function setYearPublication(?string $yearPublication): static
    {
        $this->yearPublication = $yearPublication;

        return $this;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(?string $ISBN): static
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): static
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        if ($this->authors->removeElement($author)) {
            $author->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Reader>
     */
    public function getReaders(): Collection
    {
        return $this->readers;
    }

    public function addReader(Reader $reader): static
    {
        if (!$this->readers->contains($reader)) {
            $this->readers->add($reader);
        }

        return $this;
    }

    public function removeReader(Reader $reader): static
    {
        $this->readers->removeElement($reader);

        return $this;
    }

    /**
     * @return Collection<int, DiscardBook>
     */
    public function getDiscardBook(): Collection
    {
        return $this->discardBook;
    }

    public function addDiscardBook(DiscardBook $discardBook): static
    {
        if (!$this->discardBook->contains($discardBook)) {
            $this->discardBook->add($discardBook);
            $discardBook->setBook($this);
        }

        return $this;
    }

    public function removeDiscardBook(DiscardBook $discardBook): static
    {
        if ($this->discardBook->removeElement($discardBook)) {
            // set the owning side to null (unless already changed)
            if ($discardBook->getBook() === $this) {
                $discardBook->setBook(null);
            }
        }

        return $this;
    }
}
