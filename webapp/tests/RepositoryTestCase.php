<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RepositoryTestCase extends KernelTestCase
{
    /** @var EntityManagerInterface $entityManager */
    protected $entityManager;

    protected function setUp()
    {
        parent::setUp();

        self::bootKernel();

        $this->entityManager = self::$container->get(EntityManagerInterface::class);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
