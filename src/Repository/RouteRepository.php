<?php

namespace App\Repository;

use App\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Route|null find($id, $lockMode = null, $lockVersion = null)
 * @method Route|null findOneBy(array $criteria, array $orderBy = null)
 * @method Route[]    findAll()
 * @method Route[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteRepository extends ServiceEntityRepository
{
    // ########################################

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    // ########################################

    /**
     * @param \App\Entity\User $user
     *
     * @return Route[]
     */
    public function findByUser(\App\Entity\User $user): array
    {
        return $this->findBy([
            'user' => $user,
        ]);
    }

    public function save(Route $route): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($route);
        $entityManager->flush();
    }

    // ########################################
}
