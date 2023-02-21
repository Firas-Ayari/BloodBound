<?php

namespace App\Entity;

use Assert\NotBlank;
use Doctrine\DBAL\Types\Types;
use App\Entity\ArticleCategory;
use Doctrine\ORM\Mapping as ORM;
use App\Model\TimestampedInterface;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\ArticleRepository;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message = "Please enter a title.")
     * @Assert\Length(
     *     min=5,
     *     max=30,
     *     minMessage="Le nom doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private ?string $title = null;
/**
     * @Assert\NotBlank(message = "Please enter a featured Text.")
     * @Assert\Length(
     *     min=5,
     *     max=30,
     *     minMessage="Le nom doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $featuredText = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message = "Please enter a content.")
     * @Assert\Length(
     *     min=5,
     *     max=30,
     *     minMessage="Le nom doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $updatedAt;

    #[ManyToOne(targetEntity: ArticleCategory::class, inversedBy: 'articles')]
    #[JoinColumn(name: 'articleCategory_id', referencedColumnName: 'id', onDelete:"CASCADE")]
    private ArticleCategory|null $articleCategory = null;
    public function __construct(){
        $this->createdAt=new \DateTimeImmutable();
        $this->updatedAt=new \DateTimeImmutable();
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

    public function getFeaturedText(): ?string
    {
        return $this->featuredText;
    }

    public function setFeaturedText(string $featuredText): self
    {
        $this->featuredText = $featuredText;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getArticleCategory(): ?ArticleCategory
    {
        return $this->articleCategory;
    }

    public function setArticleCategory(?ArticleCategory $articleCategory): self
    {
        $this->articleCategory = $articleCategory;

        return $this;
    }
    
    
}