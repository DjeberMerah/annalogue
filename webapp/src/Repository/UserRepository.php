<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UsersHaveModules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findBySubscribedModule(int $moduleId)
    {
        $builder = $this->createQueryBuilder('u');

        $builder
            ->innerJoin('u.usersHaveModules', 'uhm')
            ->where($builder->expr()->andX(
                $builder->expr()->eq('uhm.user', 'u'),
                $builder->expr()->eq('uhm.module', ':module_id')
            ));

        return $builder
            ->setParameter('module_id', $moduleId)
            ->getQuery()
            ->execute();
    }

    public function findByNonSubscribedModule(int $moduleId)
    {
        $sub = $this->getEntityManager()->createQueryBuilder();

        $sub
            ->select('uhm')
            ->from(UsersHaveModules::class, 'uhm')
            ->where($sub->expr()->andX(
                $sub->expr()->eq('uhm.user', 'u'),
                $sub->expr()->eq('uhm.module', ':module_id')
            ));

        $builder = $this->createQueryBuilder('u');

        $builder
            ->where($builder->expr()->not($builder->expr()->exists($sub)));

        return $builder
            ->setParameter('module_id', $moduleId)
            ->getQuery()
            ->execute();
    }
}
