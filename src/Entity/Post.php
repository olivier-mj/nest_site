<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Michelf\Markdown;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @Vich\Uploadable
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $online;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="category")
     * @ORM\JoinColumn(referencedColumnName="id",onDelete="SET NULL")
     *
     */
    private ?Category $category;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="posts", cascade={"persist"})
     */
    private Collection $tags;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     */
    private Collection $comments;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts", cascade={"persist"} ,fetch="EAGER")
     * @ORM\JoinColumn(referencedColumnName="id",nullable=false)
     *
     */
    private ?User $user = null;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable= true)
     */
    private ?string $filename = null;

    /**
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpg","image/jpeg","image/png", "image/webp"}
     * )
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="filename")
     */
    private ?File $imageFile = null;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getExcerpt(?int $nbr = null): string
    {
        if (null === $this->content) {
            return '';
        }

        if (!empty($nbr)) {
            $maxLength = $nbr;
        } else {
            $maxLength = 190;
        }

        $content = $this->content;
        $content = Markdown::defaultTransform($content);
        $content = strip_tags($content, '<p><a>');
        if (strlen($content) > $maxLength) {
            $excerpt   = substr($content, 0, $maxLength - 3);
            $lastSpace = strrpos($excerpt, ' ');
            $excerpt   = substr($excerpt, 0, (int)$lastSpace);
            /** @phpstan-ignore-line */
            $excerpt  .= '...';
        } else {
            $excerpt = $this->content;
        }

        return strip_tags($excerpt);
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getOnline(): bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection
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

    public function hasTag(Tag $tag): bool
    {
        return  $this->tags->contains($tag);
    }

    public function getTag(): iterable
    {
        return  $this->tags;
    }



    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        $slug = (new Slugify());

        return $slug->slugify($this->getTitle());
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    //    public function getAuthor(): ?User
    //    {
    //        return $this->user->getUsername();
    //    }


    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return Post
     */
    public function setFilename(?string $filename): Post
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Post
     */
    public function setImageFile(?File $imageFile = null): Post
    {
        $this->imageFile = $imageFile;

        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

}
