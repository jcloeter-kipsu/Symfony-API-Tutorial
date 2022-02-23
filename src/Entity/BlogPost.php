<?php

namespace App\Entity;

use App\Repository\BlogPostRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogPostRepository::class)]
#[ApiResource(
    itemOperations: ['get'],
    collectionOperations: ['get']
)]
class BlogPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'datetime')]
    private $published;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

     #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "posts")]
     #[ORM\JoinColumn(nullable: false)]
    private $author;

     #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: "blogPost" )]
     #[ORM\JoinColumn(nullable: false)]
     private $comments;

     public function __construct(){
         $this->comments=new ArrayCollection();
     }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }


    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): self
    {
        $this->author = $author;

        //I think we return '$this' so that we may chain commands
        return $this;
    }

//Replacing ArrayCollection with Collection because Doctrine converts to PersistantCollection
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments(ArrayCollection $comments): void
    {
        $this->comments = $comments;
    }


}
