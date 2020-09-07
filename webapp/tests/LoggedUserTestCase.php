<?php

namespace App\Tests;

use App\Entity\User;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoggedUserTestCase extends KernelTestCase
{
    /** @var UserManager $userManager */
    protected $userManager;

    /** @var User $user */
    protected $user;

    /** @var UsernamePasswordToken $token */
    protected $token;

    protected function setUp()
    {
        parent::setUp();

        self::bootKernel();

        $this->userManager = self::$container->get(UserManager::class);
    }

    protected function tearDown()
    {
        self::$container->get(TokenStorageInterface::class)->setToken(null);

        parent::tearDown();
    }

    protected function login(int $id)
    {
        $this->user = $this->userManager->get($id);

        $this->token = new UsernamePasswordToken($this->user, $this->user->getPassword(), 'main',
            $this->user->getRoles());

        self::$container->get(TokenStorageInterface::class)->setToken($this->token);
    }
}
