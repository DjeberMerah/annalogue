<?php

namespace App\Tests\Service;

use App\Entity\Module;
use App\Entity\User;
use App\Service\ModuleManager;
use App\Tests\LoggedUserTestCase;

class ModuleManagerTest extends LoggedUserTestCase
{
    /** @var ModuleManager $moduleManager */
    private $moduleManager;

    protected function setUp()
    {
        parent::setUp();

        $this->moduleManager = self::$container->get(ModuleManager::class);
    }

    public function testGetAll()
    {
        $modules = $this->moduleManager->getAll();

        $this->assertCount(5, $modules);
    }

    public function testGet()
    {
        $id = 1;

        /** @var Module $module */
        $module = $this->moduleManager->get($id);

        $this->assertEquals($id, $module->getId());
        $this->assertEquals('Test Fonctionnel', $module->getName());
    }

    public function testIsOwnedByUser()
    {
        /** @var User $resp */
        $resp = $this->userManager->get(2);
        /** @var User $admin */
        $admin = $this->userManager->get(3);
        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);
        /** @var Module $module4 */
        $module4 = $this->moduleManager->get(4);

        $this->assertTrue($this->moduleManager->isOwnedByUser($module1, $resp));
        $this->assertFalse($this->moduleManager->isOwnedByUser($module4, $resp));
        $this->assertFalse($this->moduleManager->isOwnedByUser($module1, $admin));
        $this->assertTrue($this->moduleManager->isOwnedByUser($module4, $admin));
    }

    public function testIsOwnedByLoggedUser()
    {
        $this->login(2);

        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);
        /** @var Module $module4 */
        $module4 = $this->moduleManager->get(4);

        $this->assertTrue($this->moduleManager->isOwnedByLoggedUser($module1));
        $this->assertFalse($this->moduleManager->isOwnedByLoggedUser($module4));
    }

    public function testIsUserSubscribed()
    {
        /** @var User $user */
        $user = $this->userManager->get(1);
        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);
        /** @var Module $module4 */
        $module4 = $this->moduleManager->get(4);

        $this->assertTrue($this->moduleManager->isUserSubscribed($module1, $user));
        $this->assertFalse($this->moduleManager->isUserSubscribed($module4, $user));
    }

    public function testIsLoggedUserSubscribed()
    {
        $this->login(1);

        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);
        /** @var Module $module4 */
        $module4 = $this->moduleManager->get(4);

        $this->assertTrue($this->moduleManager->isLoggedUserSubscribed($module1));
        $this->assertFalse($this->moduleManager->isLoggedUserSubscribed($module4));
    }

    public function testIsUserManager()
    {
        /** @var User $user */
        $user = $this->userManager->get(1);
        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);
        /** @var Module $module4 */
        $module4 = $this->moduleManager->get(4);

        $this->assertTrue($this->moduleManager->isUserManager($module1, $user));
        $this->assertFalse($this->moduleManager->isUserManager($module4, $user));
    }

    public function testIsLoggedUserManager()
    {
        $this->login(1);

        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);
        /** @var Module $module4 */
        $module4 = $this->moduleManager->get(4);

        $this->assertTrue($this->moduleManager->isLoggedUserManager($module1));
        $this->assertFalse($this->moduleManager->isLoggedUserManager($module4));
    }

    public function testLoggedUserCanCreate()
    {
        $this->login(1);

        $this->assertFalse($this->moduleManager->loggedUserCanCreate());

        $this->login(2);

        $this->assertTrue($this->moduleManager->loggedUserCanCreate());

        $this->login(3);

        $this->assertTrue($this->moduleManager->loggedUserCanCreate());
    }

    public function testLoggedUserCanHandle()
    {
        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);
        /** @var Module $module4 */
        $module4 = $this->moduleManager->get(4);

        $this->login(1);

        $this->assertFalse($this->moduleManager->loggedUserCanHandle($module1));
        $this->assertFalse($this->moduleManager->loggedUserCanHandle($module4));

        $this->login(2);

        $this->assertTrue($this->moduleManager->loggedUserCanHandle($module1));
        $this->assertFalse($this->moduleManager->loggedUserCanHandle($module4));

        $this->login(3);

        $this->assertTrue($this->moduleManager->loggedUserCanHandle($module1));
        $this->assertTrue($this->moduleManager->loggedUserCanHandle($module4));
    }

    public function testLoggedUserCanInteract()
    {
        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);
        /** @var Module $module4 */
        $module4 = $this->moduleManager->get(4);

        $this->login(1);

        $this->assertTrue($this->moduleManager->loggedUserCanInteract($module1));
        $this->assertFalse($this->moduleManager->loggedUserCanInteract($module4));

        $this->login(2);

        $this->assertTrue($this->moduleManager->loggedUserCanInteract($module1));
        $this->assertFalse($this->moduleManager->loggedUserCanInteract($module4));

        $this->login(3);

        $this->assertTrue($this->moduleManager->loggedUserCanInteract($module1));
        $this->assertTrue($this->moduleManager->loggedUserCanInteract($module4));
    }

    public function testGetSubscribedUsers()
    {
        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);
        /** @var User[] $users */
        $users = $this->moduleManager->getSubscribedUsers($module1);

        $this->assertCount(2, $users);
        $this->assertEquals(1, $users[0]->getId());
        $this->assertEquals(3, $users[1]->getId());
    }

    public function testGetNonSubscribedUsers()
    {
        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);
        /** @var User[] $users */
        $users = $this->moduleManager->getNonSubscribedUsers($module1);

        $this->assertCount(1, $users);
        $this->assertEquals(2, $users[0]->getId());
    }

    public function testSearchAll()
    {
        /** @var Module[] $modules */
        $modules = $this->moduleManager->searchAll('test');

        $this->assertCount(1, $modules);
        $this->assertEquals(1, $modules[0]->getId());
    }

    public function testSubscribe()
    {
        /** @var User $user1 */
        $user1 = $this->userManager->get(1);

        /** @var Module $module4 */
        $module4 = $this->moduleManager->get(4);

        $map = [
            [
                'user' => $user1,
                'flag' => false
            ]
        ];

        $this->assertFalse($this->moduleManager->isUserSubscribed($module4, $user1));

        $this->moduleManager->subscribe($module4, $map);

        $this->assertTrue($this->moduleManager->isUserSubscribed($module4, $user1));
    }

    public function testUnsubscribe()
    {
        /** @var User $user1 */
        $user1 = $this->userManager->get(1);

        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);

        $this->assertTrue($this->moduleManager->isUserSubscribed($module1, $user1));

        $this->moduleManager->unsubscribe($module1, $user1);

        $this->assertFalse($this->moduleManager->isUserSubscribed($module1, $user1));
    }

    public function testSetUserManager()
    {
        /** @var User $user1 */
        $user1 = $this->userManager->get(1);

        /** @var Module $module2 */
        $module2 = $this->moduleManager->get(2);

        $this->assertFalse($this->moduleManager->isUserManager($module2, $user1));

        $this->moduleManager->setUserManager($module2, $user1);

        $this->assertTrue($this->moduleManager->isUserManager($module2, $user1));
    }

    public function testUnsetUserManager()
    {
        /** @var User $user1 */
        $user1 = $this->userManager->get(1);

        /** @var Module $module1 */
        $module1 = $this->moduleManager->get(1);

        $this->assertTrue($this->moduleManager->isUserManager($module1, $user1));

        $this->moduleManager->unsetUserManager($module1, $user1);

        $this->assertFalse($this->moduleManager->isUserManager($module1, $user1));
    }

    public function testCreate()
    {
        $this->login(2);

        // Création d'un nouveau module
        $name = 'Test module';

        $module = new Module();

        $module->setName($name);

        $this->moduleManager->create($module);

        // Vérification de l'existence du nouveau module
        $id = $module->getId();

        $module = $this->moduleManager->get($id);

        $this->assertNotNull($module);
        $this->assertEquals($name, $module->getName());
    }

    public function testUpdate()
    {
        // Mise à jour d'un module
        $id = 1;
        $name = 'Test module';

        /** @var Module $module */
        $module = $this->moduleManager->get($id);

        $module->setName($name);

        $this->moduleManager->update($module);

        // Vérification de la mise à jour
        $module = $this->moduleManager->get($id);

        $this->assertEquals($name, $module->getName());
    }

    public function testDelete()
    {
        // Suppression d'un module
        $id = 1;

        /** @var Module $module */
        $module = $this->moduleManager->get($id);

        $this->moduleManager->delete($module);

        // Vérification de la suppression
        $module = $this->moduleManager->get($id);

        $this->assertNull($module);
    }
}
