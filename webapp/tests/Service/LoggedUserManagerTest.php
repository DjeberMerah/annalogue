<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\LoggedUserManager;
use App\Tests\LoggedUserTestCase;

class LoggedUserManagerTest extends LoggedUserTestCase
{
    /** @var LoggedUserManager $loggedUserManager */
    private $loggedUserManager;

    protected function setUp()
    {
        parent::setUp();

        $this->loggedUserManager = self::$container->get(LoggedUserManager::class);
    }

    public function testLoggedUser()
    {
        parent::login(1);

        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        $this->assertEquals($this->user->getId(), $user->getId());
        $this->assertEquals($this->user->getMail(), $user->getMail());
    }

    public function testIsGranted()
    {
        parent::login(1);
        
        $this->assertTrue($this->loggedUserManager->isGranted(['ROLE_USER']));
        $this->assertFalse($this->loggedUserManager->isGranted(['ROLE_RESP']));
    }


}
