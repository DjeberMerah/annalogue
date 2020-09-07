<?php

namespace App\Repository;

use App\Entity\Correction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Correction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Correction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Correction[]    findAll()
 * @method Correction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Correction::class);
    }
}
