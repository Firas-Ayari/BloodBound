<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\FacilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FacilityRepository::class)]
class Facility
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message = "Please enter a value.")
     * @Assert\Length(
     *     min=5,
     *     max=30,
     *     minMessage="Le nom doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private ?string $name = null;



    #[ORM\Column(length: 255)]
     /**
     * @Assert\NotBlank(message = "Please enter a value.")
     * @Assert\Length(
     *     min=5,
     *     max=40,
     *     minMessage="La description d'emplacement doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le description d'emplacement ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private ?string $address = null;



    #[ORM\Column(length: 255)]
    /**
     * @Assert\Positive
     */
    private ?string $rank = null;



    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'facilities')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getRank(): ?string
    {
        return $this->rank;
    }

    public function setRank(string $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addFacility($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFacility($this);
        }

        return $this;
    }
}
