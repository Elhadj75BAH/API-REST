<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("user:read")
     * @Groups("client:read")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("user:read")
     * @Groups("client:read")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("user:read")
     * @Groups("client:read")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("user:read")
     * @Groups("client:read")
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("user:read")
     * @Groups("client:read")
     * @Assert\NotBlank(message="ce champ ne peut pas être null")
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="users")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("user:read")
     */
    private $client;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

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

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->product->removeElement($product);

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
