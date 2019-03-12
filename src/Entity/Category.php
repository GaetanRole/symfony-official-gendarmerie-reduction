<?php

/**
 * Category Entity File
 *
 * PHP Version 7.2
 *
 * @category    Category
 * @package     App\Entity
 * @version     1.0
 * @author      Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category Entity Class
 *
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 *
 * @category    Category
 * @package     App\Entity
 * @author      Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */
class Category implements \JsonSerializable
{
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
     * @var string
     *
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank(message="Un nom de catégorie doit être renseigné.")
     * @Assert\Length(
     *     min=2,
     *     minMessage="Le nom de la catégorie est bien trop court ({{ limit }} min).",
     *     max=64,
     *     maxMessage="Le nom de la catégorie est bien trop long ({{ limit }} max)."
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *     max=255,
     *     maxMessage="La description est bien longue ? ({{ limit }} max)."
     * )
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToMany(targetEntity="Reduction", mappedBy="categories")
     */
    private $reductions;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->reductions = new ArrayCollection();
        $this->description = 'Aucune description renseignée.';
    }

    /*
    * Personal methods
    */

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): string
    {
        // http://php.net/manual/en/class.jsonserializable.php
        // e.g. categories|json_encode
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Category
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
     * @return Category
     */
    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return Collection|Reduction[]
     */
    public function getReductions(): Collection
    {
        return $this->reductions;
    }

    /**
     * @param Reduction $reduction
     * @return Category
     */
    public function addReduction(Reduction $reduction): self
    {
        if (!$this->reductions->contains($reduction)) {
            $this->reductions[] = $reduction;
            $reduction->addCategory($this);
        }

        return $this;
    }

    /**
     * @param Reduction $reduction
     * @return Category
     */
    public function removeReduction(Reduction $reduction): self
    {
        if ($this->reductions->contains($reduction)) {
            $this->reductions->removeElement($reduction);
            $reduction->removeCategory($this);
        }

        return $this;
    }
}
