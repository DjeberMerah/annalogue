<?php

namespace App\Tests\Service;

use App\Entity\Module;
use App\Entity\User;
use App\Tests\LoggedUserTestCase;

class UserManagerTest extends LoggedUserTestCase
{
    public function testGetAll()
    {
        $users = $this->userManager->getAll();

        $this->assertCount(3, $users);
    }

    public function testGet()
    {
        $id = 1;

        /** @var User $user */
        $user = $this->userManager->get($id);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals('user@univ-fcomte.fr', $user->getMail());
        $this->assertEquals('Basic user', $user->getName());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testGetNull()
    {
        $id = 42;

        /** @var User $user */
        $user = $this->userManager->get($id);

        $this->assertNull($user);
    }

    public function testGetSubscribedModules()
    {
        /** @var User $user */
        $user = $this->userManager->get(1);
        /** @var Module[] $modules */
        $modules = $this->userManager->getSubscribedModules($user);

        $this->assertCount(3, $modules);
        $this->assertEquals(1, $modules[0]->getId());
        $this->assertEquals(2, $modules[1]->getId());
        $this->assertEquals(3, $modules[2]->getId());
    }

    public function testSearchSubscribedModules()
    {
        /** @var User $user */
        $user = $this->userManager->get(1);
        /** @var Module[] $modules */
        $modules = $this->userManager->searchSubscribedModules($user, 'test');

        $this->assertCount(1, $modules);
        $this->assertEquals(1, $modules[0]->getId());
    }

    public function testCreate()
    {
        // Création d'un nouvel utilisateur
        $mail = 'test@univ-fcomte.fr';
        $name = 'Test user';

        $user = new User();

        $user->setMail($mail);
        $user->setName($name);
        $user->setPassword('test');
        $user->setRoles(['ROLE_USER']);

        $this->userManager->create($user);

        // Vérification de l'existence du nouvel utilisateur
        $id = $user->getId();

        $user = $this->userManager->get($id);

        $this->assertNotNull($user);
        $this->assertEquals($mail, $user->getMail());
        $this->assertEquals($name, $user->getName());
    }

    public function testUpdate()
    {
        // Mise à jour d'un utilisateur
        $id = 1;
        $name = 'Test user';

        /** @var User $user */
        $user = $this->userManager->get($id);

        $user->setName($name);

        $this->userManager->update($user);

        // Vérification de la mise à jour
        $user = $this->userManager->get($id);

        $this->assertEquals($name, $user->getName());
    }

    public function testDelete()
    {
        // Suppression d'un utilisateur
        $id = 1;

        /** @var User $user */
        $user = $this->userManager->get($id);

        $this->userManager->delete($user);

        // Vérification de la suppression
        $user = $this->userManager->get($id);

        $this->assertNull($user);
    }
}
