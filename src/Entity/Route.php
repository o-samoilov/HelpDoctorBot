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

    // ########################################

    public function getId(): ?int
    {
        return $this->id;
    }

    // ########################################
}
