<?php

namespace App\Repository;

use App\Entity\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method District|null find($id, $lockMode = null, $lockVersion = null)
 * @method District|null findOneBy(array $criteria, array $orderBy = null)
 * @method District[]    findAll()
 * @method District[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DistrictRepository extends ServiceEntityRepository
{
    // ########################################

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, District::class);
    }

    // ########################################

    public function findByIdAndCity(int $id, \App\Entity\City $city): District
    {
        return $this->findOneBy([
            'id'   => $id,
            'city' => $city->getId(),
        ]);
    }

    // ########################################
}
