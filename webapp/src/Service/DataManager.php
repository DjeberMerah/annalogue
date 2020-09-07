<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

abstract class DataManager
{
    protected $entityManager;
    protected $repository;

    public function __construct(EntityManagerInterface $entityManager, string $entity)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($entity);
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }

    public function get(int $id)
    {
        return $this->repository->find($id);
    }
}
