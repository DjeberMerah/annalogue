<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\RepositoryTestCase;

class UserRepositoryTest extends RepositoryTestCase
{
    /** @var UserRepository $repository */
    private $repository;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function testFindBySubscribedModule()
    {
        /** @var User[] $users */
        $users = $this->repository->findBySubscribedModule(1);

        $this->assertCount(2, $users);
        $this->assertEquals(1, $users[0]->getId());
        $this->assertEquals(3, $users[1]->getId());
    }

    public function testFindByNonSubscribedModule()
    {
        /** @var User[] $users */
        $users = $this->repository->findByNonSubscribedModule(2);

        $this->assertCount(2, $users);
        $this->assertEquals(2, $users[0]->getId());
        $this->assertEquals(3, $users[1]->getId());
    }
}
