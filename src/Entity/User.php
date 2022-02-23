<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraint as Assert;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    itemOperations: ["get"],
    collectionOperations: ["get", "post"],
    normalizationContext: ['groups'=>'read']
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups("read")]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups("read")]
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $username;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $password;

    #[Groups("read")]
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private $email;

    #[Groups("read")]
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: "author")]
    private $comments;

    #[Groups("read")]
    #[ORM\OneToMany(targetEntity: BlogPost::class, mappedBy: "author")]
    private $posts;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getComments():Collection
    {
        return $this->comments;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        //We don't need this function because there is no session to keep credentials
        //Each API request will be validated individually
    }

    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.
        return $this->username;
    }
}
