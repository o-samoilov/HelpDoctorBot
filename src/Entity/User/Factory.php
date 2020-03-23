<?php

declare(strict_types=1);

namespace App\Entity\User;

class Factory
{
    // ########################################

    public function createDriver(
        int $pipeUid,
        string $description,
        \App\Entity\City $city
    ): \App\Entity\User {
        return new \App\Entity\User(
            $pipeUid,
            \App\Entity\User::ROLE_DRIVER,
            $description,
            $city
        );
    }

    // ########################################

    public function createDoctor(
        int $pipeUid,
        string $description,
        \App\Entity\City $city
    ): \App\Entity\User {
        return new \App\Entity\User(
            $pipeUid,
            \App\Entity\User::ROLE_DOCTOR,
            $description,
            $city
        );
    }

    // ########################################
}
