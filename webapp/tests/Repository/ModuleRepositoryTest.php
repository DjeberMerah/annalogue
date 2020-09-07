<?php

namespace App\Tests\Repository;

use App\Entity\Module;
use App\Repository\ModuleRepository;
use App\Tests\RepositoryTestCase;

class ModuleRepositoryTest extends RepositoryTestCase
{
    /** @var ModuleRepository $repository */
    private $repository;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->entityManager->getRepository(Module::class);
    }

    public function testFindBySubscribedUser()
    {
        /** @var Module[] $modules */
        $modules = $this->repository->findBySubscribedUser(1);

        $this->assertCount(3, $modules);
        $this->assertEquals(1, $modules[0]->getId());
        $this->assertEquals(2, $modules[1]->getId());
        $this->assertEquals(3, $modules[2]->getId());
    }

    public function testIsSubscribed()
    {
        $isSubscribed1 = $this->repository->isSubscribed(1, 1);
        $isSubscribed2 = $this->repository->isSubscribed(1, 3);
        $isSubscribed3 = $this->repository->isSubscribed(4, 1);
        $isSubscribed4 = $this->repository->isSubscribed(4, 3);

        $this->assertTrue($isSubscribed1);
        $this->assertTrue($isSubscribed2);
        $this->assertFalse($isSubscribed3);
        $this->assertTrue($isSubscribed4);
    }

    public function testIsManager()
    {
        $isManager1 = $this->repository->isManager(1, 1);
        $isManager2 = $this->repository->isManager(2, 1);

        $this->assertTrue($isManager1);
        $this->assertFalse($isManager2);
    }

    public function testSearchByName()
    {
        /** @var Module[] $modules */
        $modules = $this->repository->searchByName('test');

        $this->assertCount(1, $modules);
        $this->assertEquals(1, $modules[0]->getId());
    }

    public function testSearchSubscribedByName()
    {
        /** @var Module[] $modules */
        $modules = $this->repository->searchSubscribedByName('test', 1);
        
        $this->assertCount(1, $modules);
        $this->assertEquals(1, $modules[0]->getId());
    }
}
