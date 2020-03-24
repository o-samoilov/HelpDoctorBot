<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RouteRepository")
 * @ORM\Table(name="route")
 */
class Route
{
    // ########################################

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \App\Entity\District
     * @ORM\ManyToOne(targetEntity="App\Entity\District")
     * @ORM\JoinColumn(name="from_district", referencedColumnName="id", nullable=false)
     */
    protected $fromDistrict;

    /**
     * @var string
     * @ORM\Column(type="string", name="from_comment", nullable=true)
     */
    protected $fromComment;

    /**
     * @var \App\Entity\District
     * @ORM\ManyToOne(targetEntity="App\Entity\District")
     * @ORM\JoinColumn(name="to_district", referencedColumnName="id", nullable=false)
     */
    protected $toDistrict;

    /**
     * @var string
     * @ORM\Column(type="string", name="to_comment", nullable=true)
     */
    protected $toComment;

    /**
     * @var string
     * @ORM\Column(type="string", name="time", nullable=false)
     */
    protected $time;

    /**
     * @var string
     * @ORM\Column(type="string", name="date", nullable=false)
     */
    protected $date;

    /**
     * @var \App\Entity\City
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=false)
     */
    protected $city;

    // ########################################

    public function __construct()
    {
    }

    // ########################################

    public function getId(): ?int
    {
        return $this->id;
    }

    // ########################################

    public function getFromDistrict(): \App\Entity\District
    {
        return $this->fromDistrict;
    }

    public function setFromDistrict(\App\Entity\District $fromDistrict): self
    {
        $this->fromDistrict = $fromDistrict;

        return $this;
    }

    // ########################################

    public function getFromComment(): ?string
    {
        return $this->fromComment;
    }

    public function setFromComment(?string $fromComment): self
    {
        $this->fromComment = $fromComment;

        return $this;
    }

    // ########################################

    public function getToDistrict(): \App\Entity\District
    {
        return $this->toDistrict;
    }

    public function setToDistrict(\App\Entity\District $toDistrict): self
    {
        $this->toDistrict = $toDistrict;

        return $this;
    }

    // ########################################

    public function getToComment(): ?string
    {
        return $this->toComment;
    }

    public function setToComment(?string $toComment): self
    {
        $this->toComment = $toComment;

        return $this;
    }

    // ########################################

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    // ########################################

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

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
