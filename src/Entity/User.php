<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    private string $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $nickname;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $twitch;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $youtube;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $steam;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user")
     */
    private Collection $posts;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private Collection $comments;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable= true)
     */
    private ?string $filename;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="filename")
     */
    private ?File $imageFile = null;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="author")
     */
    private Collection $videos;


    public function __construct()
    {
        $this->createdAt = new DateTime();
//        $this->isActive = true;
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->videos = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials() :void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTwitch(): ?string
    {
        return $this->twitch;
    }

    public function setTwitch(?string $twitch): self
    {
        $this->twitch = $twitch;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getSteam(): ?string
    {
        return $this->steam;
    }

    public function setSteam(?string $steam): self
    {
        $this->steam = $steam;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return User
     */
    public function setFilename(?string $filename): User
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
     * @return User
     */
    public function setImageFile(?File $imageFile): User
    {
        $this->imageFile = $imageFile;

        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

    public function getSlug(): ?string
    {
        $slug = (new Slugify());

        return $slug->slugify($this->getUsername());

    }

    /**
     * @return Collection|ArrayCollection
     */
    public function getPosts(): ArrayCollection|Collection
    {
        return $this->posts;
    }

    /**
     * @param Collection|ArrayCollection $posts
     * @return User
     */
    public function setPosts(ArrayCollection|Collection $posts): User
    {
        $this->posts = $posts;
        return $this;
    }

    /**
     * @return Collection|ArrayCollection
     */
    public function getComments(): ArrayCollection|Collection
    {
        return $this->comments;
    }

    /**
     * @param Collection|ArrayCollection $comments
     * @return User
     */
    public function setComments(ArrayCollection|Collection $comments): User
    {
        $this->comments = $comments;
        return $this;
    }

    public function getTitle(): string
    {
        return (string)$this->username;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setAuthor($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getAuthor() === $this) {
                $video->setAuthor(null);
            }
        }

        return $this;
    }
}
