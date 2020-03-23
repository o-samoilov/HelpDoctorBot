<?php

declare(strict_types=1);

namespace App\Entity\User;

class Factory
{
    // ########################################

    public function createDriver(
        int $pipeUid,
        int $telegramUid,
        string $username,
        string $firstName,
        string $lastName,
        string $description,
        \App\Entity\City $city
    ): \App\Entity\User {
        return new \App\Entity\User(
            $pipeUid,
            $telegramUid,
            $username,
            $firstName,
            $lastName,
            \App\Entity\User::ROLE_DRIVER,
            $description,
            $city
        );
    }

    // ########################################

    public function createDoctor(
        int $pipeUid,
        int $telegramUid,
        string $username,
        string $firstName,
        string $lastName,
        string $description,
        \App\Entity\City $city
    ): \App\Entity\User {
        return new \App\Entity\User(
            $pipeUid,
            $telegramUid,
            $username,
            $firstName,
            $lastName,
            \App\Entity\User::ROLE_DOCTOR,
            $description,
            $city
        );
    }

    // ########################################
}
