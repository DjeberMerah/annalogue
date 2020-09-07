<?php

namespace App\Repository;

use App\Entity\UsersHaveModules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UsersHaveModules|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersHaveModules|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersHaveModules[]    findAll()
 * @method UsersHaveModules[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersHaveModulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersHaveModules::class);
    }
}
