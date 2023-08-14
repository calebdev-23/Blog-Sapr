<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Interface\AuthoredEntityInterface;
use App\Interface\PublishedDateEntityInterface;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource(
    operations: [

        new GetCollection(),
        new Get(
           normalizationContext: ["groups"=>["read:comment"]],
        ),
        new Post(
            denormalizationContext: ["groups"=>["post:comment"]],
            security: "is_granted('IS_AUTHENTICATED_FULLY')"
        ),
        new Put(
            security: "is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() == user"
        )
    ],
)]

class Comment implements AuthoredEntityInterface, PublishedDateEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:comment", "get-all-comment"])]
    private ?int $id = null;
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(
        min:5,
    )]
    #[Groups(["post:comment", "get-all-comment", "read:comment", "read:comment:user"])]
    private ?string $content = null;
    #[Groups(["get-all-comment", "read:comment", "read:comment:user"])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $published = null;
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[Groups(["get-all-comment",])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?BlogPost $BlogPost = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface
    {
        $this->published = $published;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->user;
    }

    public function setAuthor(UserInterface $user): AuthoredEntityInterface
    {
        $this->user = $user;

        return $this;
    }

    public function getBlogPost(): ?BlogPost
    {
        return $this->BlogPost;
    }

    public function setBlogPost(?BlogPost $BlogPost): static
    {
        $this->BlogPost = $BlogPost;

        return $this;
    }
}
