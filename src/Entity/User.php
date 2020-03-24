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

    /** @ORM\Column(type="integer", name="pipe_uid", unique=true, nullable=false) */
    protected $pipeUid;

    /** @ORM\Column(type="integer", name="role", nullable=false) */
    protected $role;

    /** @ORM\Column(type="string", name="description", nullable=true) */
    protected $description;

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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

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
