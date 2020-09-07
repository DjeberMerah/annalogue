<?php

namespace App\Repository;

use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    public function findBySubscribedUser(int $userId)
    {
        $builder = $this->createQueryBuilder('m');

        $builder
            ->addSelect('u')
            ->innerJoin('m.owner', 'u')
            ->leftJoin('m.usersHaveModules', 'uhm')
            ->where($builder->expr()->orX(
                $builder->expr()->eq('m.owner', ':user_id'),
                $builder->expr()->andX(
                    $builder->expr()->eq('uhm.module', 'm'),
                    $builder->expr()->eq('uhm.user', ':user_id')
                )
            ));

        return $builder
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->execute();
    }

    public function isSubscribed(int $id, int $userId)
    {
        $builder = $this->createQueryBuilder('m');

        $builder
            ->innerJoin('m.usersHaveModules', 'uhm')
            ->where($builder->expr()->andX(
                $builder->expr()->eq('uhm.module', ':module_id'),
                $builder->expr()->eq('uhm.user', ':user_id')
            ));

        return ($builder
                ->setParameter('module_id', $id)
                ->setParameter('user_id', $userId)
                ->getQuery()
                ->execute()) != null;
    }

    public function isManager(int $id, int $userId)
    {
        $builder = $this->createQueryBuilder('m');

        $builder
            ->innerJoin('m.usersHaveModules', 'uhm')
            ->where($builder->expr()->andX(
                $builder->expr()->eq('uhm.module', ':module_id'),
                $builder->expr()->eq('uhm.user', ':user_id'),
                $builder->expr()->eq('uhm.flag', '1')
            ));

        return ($builder
                ->setParameter('module_id', $id)
                ->setParameter('user_id', $userId)
                ->getQuery()
                ->execute()) != null;
    }

    public function searchByName(string $name)
    {
        $builder = $this->createQueryBuilder('m');

        $builder
            ->where($builder->expr()->like('m.name', ':name'));

        return $builder
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->execute();
    }

    public function searchSubscribedByName(string $name, int $userId)
    {
        $builder = $this->createQueryBuilder('m');

        $builder
            ->addSelect('u')
            ->innerJoin('m.owner', 'u')
            ->leftJoin('m.usersHaveModules', 'uhm')
            ->where($builder->expr()->andX(
                $builder->expr()->orX(
                    $builder->expr()->eq('m.owner', ':user_id'),
                    $builder->expr()->andX(
                        $builder->expr()->eq('uhm.module', 'm'),
                        $builder->expr()->eq('uhm.user', ':user_id')
                    )
                ),
                $builder->expr()->like('m.name', ':name')
            ));

        return $builder
            ->setParameter('user_id', $userId)
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->execute();
    }
}
