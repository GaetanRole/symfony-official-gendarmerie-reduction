<?php

declare(strict_types=1);

namespace App\Entity;

use \Serializable;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityTimeTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @see https://symfony.com/doc/current/reference/constraints/UniqueEntity.html#fields
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"username"},
 *     message="validator.user.unique.username.message"
 * )
 * @UniqueEntity(
 *     fields={"email"},
 *     message="validator.user.unique.email.message"
 * )
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */
class User implements UserInterface, Serializable, EntityInterface
{
    use EntityIdTrait;
    use EntityTimeTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank(message="validator.user.username.not_blank")
     * @Assert\Length(
     *     min=2,
     *     minMessage="validator.user.username.min_length",
     *     max=64,
     *     maxMessage="validator.user.username.max_length"
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="validator.user.identity.not_blank")
     * @Assert\Length(
     *     min=2,
     *     minMessage="validator.user.identity.min_length",
     *     max=64,
     *     maxMessage="validator.user.identity.max_length"
     * )
     */
    private $identity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, unique=true, nullable=true)
     * @Assert\Email(
     *     message="validator.user.email.email"
     * )
     * @Assert\Length(
     *     max=64,
     *     maxMessage="validator.user.email.max_length"
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Regex(
     *     pattern="/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/",
     * message="validator.user.phone_number.regex")
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=25)
     * @Assert\Regex(pattern="/^user-avatar-.*$/", message="validator.user.avatar.regex")
     */
    private $avatar;

    /**
     * @Assert\Length(max=4096)
     * @Assert\Regex(pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/",
     * message="validator.user.plain_password.regex")
     */
    private $plainPassword;

    /**
     * @var string The hashed password
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isActive = false;
    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Reduction::class, mappedBy="user")
     */
    private $reductions;

    /**
     * @ORM\OneToMany(targetEntity=Opinion::class, mappedBy="user")
     */
    private $opinions;

    public function __construct()
    {
        $this->reductions = new ArrayCollection();
        $this->opinions = new ArrayCollection();
        $this->roles[] = 'ROLE_USER';
    }

    public function __toString(): string
    {
        return $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     */
    public function getIdentity(): ?string
    {
        return $this->identity;
    }

    public function setIdentity(?string $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password): void
    {
        $this->plainPassword = $password;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function hasRole(string $role): bool
    {
        if (\in_array($role, $this->roles, true)) {
            return true;
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('ROLE_ADMIN')
            || $this->hasRole('ROLE_SUPER_ADMIN');
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // https://symfony.com/doc/current/cookbook/security/entity_provider.html
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|Reduction[]
     */
    public function getReductions(): Collection
    {
        return $this->reductions;
    }

    public function addReduction(Reduction $reduction): self
    {
        if (!$this->reductions->contains($reduction)) {
            $this->reductions[] = $reduction;
            $reduction->setUser($this);
        }

        return $this;
    }

    public function removeReduction(Reduction $reduction): self
    {
        if ($this->reductions->contains($reduction)) {
            $this->reductions->removeElement($reduction);
            if ($this === $reduction->getUser()) {
                $reduction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Opinion[]
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions[] = $opinion;
            $opinion->setUser($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->contains($opinion)) {
            $this->opinions->removeElement($opinion);
            if ($this === $opinion->getUser()) {
                $opinion->setUser(null);
            }
        }

        return $this;
    }
}
