<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
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

    /** @ORM\Column(type="integer", name="telegram_uid", unique=true, nullable=false) */
    protected $telegramUid;

    /** @ORM\Column(type="string", length=130, name="username", unique=true, nullable=false) */
    protected $username;

    /** @ORM\Column(type="string", length=255, name="first_name", nullable=false) */
    protected $firstName;

    /** @ORM\Column(type="string", length=255, name="last_name", nullable=false) */
    protected $lastName;

    /** @ORM\Column(type="integer", name="role", nullable=false) */
    protected $role;

    /** @ORM\Column(type="string", name="description", nullable=true) */
    protected $description;

    // ########################################

    public function __construct(
        int $pipeUid,
        int $telegramUid,
        string $username,
        string $firstName,
        string $lastName,
        int $role,
        string $description
    ) {
        $this->pipeUid     = $pipeUid;
        $this->telegramUid = $telegramUid;
        $this->username    = $username;
        $this->firstName   = $firstName;
        $this->lastName    = $lastName;
        $this->role        = $role;
        $this->description = $description;
    }

    // ########################################

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPipeUid(): int
    {
        return $this->pipeUid;
    }

    public function getTelegramUid(): int
    {
        return $this->telegramUid;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

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

    public function getDescription(): string
    {
        return $this->description;
    }

    // ########################################
}
