<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User
{
    public const ROLE_DRIVER = 1;
    public const ROLE_DOCTOR = 2;

    // ########################################

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer", name="pipe_uid", unique=true, nullable=false)
     */
    protected $pipeUid;

    /**
     * @var int
     * @ORM\Column(type="integer", name="role", nullable=false)
     */
    protected $role;

    /**
     * @var string
     * @ORM\Column(type="string", name="username", nullable=false)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", name="first_name", nullable=false)
     */
    protected $firstName;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="last_name", nullable=true)
     */
    protected $lastName;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="phone", nullable=true)
     */
    protected $phone;

    /**
     * @var \App\Entity\City
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=false)
     */
    protected $city;

    // ########################################

    public function getId(): ?int
    {
        return $this->id;
    }

    // ########################################

    public function getPipeUid(): int
    {
        return $this->pipeUid;
    }

    public function setPipeUid($pipeUid): self
    {
        $this->pipeUid = $pipeUid;

        return $this;
    }

    // ########################################

    public function getRole(): int
    {
        return $this->role;
    }

    public function isRoleDriver(): bool
    {
        return $this->role === self::ROLE_DRIVER;
    }

    public function isRoleDoctor(): bool
    {
        return $this->role === self::ROLE_DOCTOR;
    }

    public function markRoleDriver(): self
    {
        $this->role = self::ROLE_DRIVER;

        return $this;
    }

    public function markRoleDoctor(): self
    {
        $this->role = self::ROLE_DOCTOR;

        return $this;
    }

    // ########################################

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    // ########################################

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    // ########################################

    public function hasLastName(): bool
    {
        return $this->lastName !== null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    // ########################################

    public function hasPhone(): bool
    {
        return $this->phone !== null;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    // ########################################

    public function getCity(): \App\Entity\City
    {
        return $this->city;
    }

    public function setCity(\App\Entity\City $city): self
    {
        $this->city = $city;

        return $this;
    }

    // ########################################
}
