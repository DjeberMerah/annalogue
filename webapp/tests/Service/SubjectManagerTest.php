<?php

namespace App\Tests\Service;

use App\Entity\Module;
use App\Entity\Subject;
use App\Entity\User;
use App\Service\ModuleManager;
use App\Service\SubjectManager;
use App\Tests\LoggedUserTestCase;

class SubjectManagerTest extends LoggedUserTestCase
{
    /** @var ModuleManager $moduleManager */
    private $moduleManager;
    /** @var SubjectManager $subjectManager */
    private $subjectManager;

    /** @var string $documentsDirectory */
    private $documentsDirectory;

    protected function setUp()
    {
        parent::setUp();

        $this->moduleManager = self::$container->get(ModuleManager::class);
        $this->subjectManager = self::$container->get(SubjectManager::class);

        $this->documentsDirectory = self::$container->getParameter('documents_directory');
    }

    public function testGetAll()
    {
        $subjects = $this->subjectManager->getAll();

        $this->assertCount(3, $subjects);
    }

    public function testGet()
    {
        $id = 1;

        /** @var Subject $subject */
        $subject = $this->subjectManager->get($id);

        $this->assertEquals($id, $subject->getId());
        $this->assertEquals('Tests unitaires web', $subject->getName());
    }

    public function testIsOwnedByUser()
    {
        /** @var User $resp */
        $resp = $this->userManager->get(2);
        /** @var User $admin */
        $admin = $this->userManager->get(3);
        /** @var Subject $subject1 */
        $subject1 = $this->subjectManager->get(1);

        $this->assertTrue($this->subjectManager->isOwnedByUser($subject1, $resp));
        $this->assertFalse($this->subjectManager->isOwnedByUser($subject1, $admin));
    }

    public function testIsOwnedByLoggedUser()
    {
        $this->login(2);

        /** @var Subject $subject1 */
        $subject1 = $this->subjectManager->get(1);

        $this->assertTrue($this->subjectManager->isOwnedByLoggedUser($subject1));
    }

    public function testCreate()
    {
        $this->login(2);

        /** @var Module $module */
        $module = $this->moduleManager->get(1);

        // Création d'un nouveau sujet
        $name = 'Test sujet';
        $description = 'Description sujet';
        $type = 'Cours';
        $document = 'tmp.pdf';

        copy('tests/Service/file.pdf', $this->documentsDirectory . '/' . $document);

        $subject = new Subject();

        $subject->setName($name);
        $subject->setDescription($description);
        $subject->setType($type);
        $subject->setDocument($document);

        $this->subjectManager->create($module, $subject);

        // Vérification de l'existence du nouveau sujet
        $id = $subject->getId();

        $subject = $this->subjectManager->get($id);

        $this->assertNotNull($subject);
        $this->assertEquals($name, $subject->getName());

        unlink($this->documentsDirectory . '/' . $document);
    }

    public function testUpdate()
    {
        // Mise à jour d'un sujet
        $id = 1;
        $name = 'Test sujet';

        /** @var Subject $subject */
        $subject = $this->subjectManager->get($id);

        $subject->setName($name);

        $this->subjectManager->update($subject);

        // Vérification de la mise à jour
        $subject = $this->subjectManager->get($id);

        $this->assertEquals($name, $subject->getName());
    }

    public function testDelete()
    {
        // Suppression d'un sujet
        $id = 1;

        /** @var Subject $subject */
        $subject = $this->subjectManager->get($id);

        $document = $subject->getDocument();
        $tmp = 'tmp.pdf';

        copy($this->documentsDirectory . '/' . $document, $this->documentsDirectory . '/' . $tmp);

        $this->subjectManager->delete($subject);

        // Vérification de la suppression
        $subject = $this->subjectManager->get($id);

        $this->assertNull($subject);
        $this->assertFileNotExists($this->documentsDirectory . '/' . $document);

        rename($this->documentsDirectory . '/' . $tmp, $this->documentsDirectory . '/' . $document);
    }
}
