<?php

/**
 * Reduction Entity File
 *
 * PHP Version 7.2
 *
 * @category    Reduction
 * @package     App\Entity
 * @version     1.0
 * @author      Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */

namespace App\Entity;

use App\Entity\MappedSuperClass\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reduction Entity Class
 *
 * @ORM\Entity(repositoryClass="App\Repository\ReductionRepository")
 *
 * @see UserIdentity To have user identity
 * @category    Reduction
 * @package     App\Entity
 * @author      Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */
class Reduction extends UserIdentity
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them under parameters section in config/services.yaml file.
     *
     * See https://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    public const NUM_ITEMS = 10;

    /*
     * Autogenerated methods / variables
     */

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reductions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Brand
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand", inversedBy="reductions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    /**
     * @var Category[]|ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Category",
     *     inversedBy="reductions",
     *     cascade={"persist"}
     * )
     * @ORM\OrderBy({"name": "ASC"})
     * @Assert\Count(
     *     min = "1",
     *     max = "3",
     *     minMessage = "{{ limit }} catégorie au minimum doit être ajoutée.",
     *     maxMessage = "{{ limit }} catégories au maximum peuvent être ajoutées."
     * )
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank(message="Un titre doit être renseigné pour poster une reduction.")
     * @Assert\Length(
     *     min=5,
     *     minMessage="Le titre est bien trop court ({{ limit }} min).",
     *     max=64,
     *     maxMessage="Le titre est bien trop long ({{ limit }} max)."
     * )
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description de la réduction doit être renseignée.")
     * @Assert\Length(
     *     min=10,
     *     minMessage="Votre réduction est bien trop courte ({{ limit }} min).",
     *     max=10000,
     *     maxMessage="Votre réduction est bien trop longue ({{ limit }} max)."
     * )
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="Un département doit être renseigné pour poster la réduction.")
     * @Assert\Length(
     *     min=3,
     *     minMessage="Le nom du département est trop court ({{ limit }} min).",
     *     max=32,
     *     maxMessage="Le nom du département est trop long ({{ limit }} max)."
     * )
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Regex(
     *     pattern="(^none$)",
     *     match=false,
     *     message="Cette ville n'éxiste pas. Veuillez vérifier celle-ci."
     * )
     * @Assert\NotBlank(message="Une ville doit être renseignée pour poster la réduction.")
     * @Assert\Length(
     *     min=2,
     *     minMessage="Le nom de la ville est trop court ({{ limit }} min).",
     *     max=64,
     *     maxMessage="Le nom de la ville est trop long ({{ limit }} max)."
     * )
     */
    private $city;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isBigDeal;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Opinion",
     *     mappedBy="reduction",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @ORM\OrderBy({"creationDate": "DESC"})
     */
    private $opinions;

    /**
     * Reduction constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->opinions = new ArrayCollection();
        $this->isBigDeal = false;
        $this->isActive = false;
    }

    /*
     * Personal methods
     */

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() . ' : ' .  $this->brand->getName();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return Reduction
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Brand|null
     */
    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand|null $brand
     * @return Reduction
     */
    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param Category ...$categories
     * @return Reduction
     */
    public function addCategory(Category ...$categories): self
    {
        foreach ($categories as $category) {
            if (!$this->categories->contains($category)) {
                $this->categories->add($category);
            }
        }

        return $this;
    }

    /**
     * @param Category $category
     * @return Reduction
     */
    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Reduction
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return Reduction
     */
    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDepartment(): ?string
    {
        return $this->department;
    }

    /**
     * @param string $department
     * @return Reduction
     */
    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Reduction
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTimeInterface $creationDate
     * @return Reduction
     */
    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsBigDeal(): ?bool
    {
        return $this->isBigDeal;
    }

    /**
     * @param bool $isBigDeal
     * @return Reduction
     */
    public function setIsBigDeal(bool $isBigDeal): self
    {
        $this->isBigDeal = $isBigDeal;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return Reduction
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Opinion[]
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    /**
     * @param Opinion $opinion
     * @return Reduction
     */
    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions[] = $opinion;
            $opinion->setReduction($this);
        }

        return $this;
    }

    /**
     * @param Opinion $opinion
     * @return Reduction
     */
    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->contains($opinion)) {
            $this->opinions->removeElement($opinion);
            if ($opinion->getReduction() === $this) {
                $opinion->setReduction(null);
            }
        }

        return $this;
    }
}
