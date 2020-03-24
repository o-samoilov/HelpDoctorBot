<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DistrictRepository")
 * @ORM\Table(name="district")
 */
class District
{
    // ########################################

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string", length=160, name="name", nullable=false) */
    protected $name;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function getCity(): \App\Entity\City
    {
        return $this->city;
    }

    // ########################################
}
