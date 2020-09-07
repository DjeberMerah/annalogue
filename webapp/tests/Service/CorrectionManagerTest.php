<?php

namespace App\Tests\Service;

use App\Entity\Correction;
use App\Entity\Subject;
use App\Entity\User;
use App\Service\CorrectionManager;
use App\Service\SubjectManager;
use App\Tests\LoggedUserTestCase;

class CorrectionManagerTest extends LoggedUserTestCase
{
    /** @var SubjectManager $subjectManager */
    private $subjectManager;
    /** @var CorrectionManager $correctionManager */
    private $correctionManager;

    /** @var string $documentsDirectory */
    private $documentsDirectory;

    protected function setUp()
    {
        parent::setUp();

        $this->subjectManager = self::$container->get(SubjectManager::class);
        $this->correctionManager = self::$container->get(CorrectionManager::class);

        $this->documentsDirectory = self::$container->getParameter('documents_directory');
    }

    public function testGetAll()
    {
        $corrections = $this->correctionManager->getAll();

        $this->assertCount(3, $corrections);
    }

    public function testGet()
    {
        $id = 1;

        /** @var Correction $correction */
        $correction = $this->correctionManager->get($id);

        $this->assertEquals($id, $correction->getId());
        $this->assertEquals('Correction 1', $correction->getName());
    }

    public function testIsOwnedByUser()
    {
        /** @var User $resp */
        $resp = $this->userManager->get(2);
        /** @var User $admin */
        $admin = $this->userManager->get(3);
        /** @var Correction $correction1 */
        $correction1 = $this->correctionManager->get(1);

        $this->assertTrue($this->correctionManager->isOwnedByUser($correction1, $resp));
        $this->assertFalse($this->correctionManager->isOwnedByUser($correction1, $admin));
    }

    public function testIsOwnedByLoggedUser()
    {
        $this->login(2);

        /** @var Correction $correction1 */
        $correction1 = $this->correctionManager->get(1);

        $this->assertTrue($this->correctionManager->isOwnedByLoggedUser($correction1));
    }

    public function testCreate()
    {
        $this->login(2);

        /** @var Subject $subject */
        $subject = $this->subjectManager->get(1);

        // Création d'une nouvelle correction
        $name = 'Test correction';
        $description = 'Description correction';
        $document = 'tmp.pdf';

        copy('tests/Service/file.pdf', $this->documentsDirectory . '/' . $document);

        $correction = new Correction();

        $correction->setName($name);
        $correction->setDescription($description);
        $correction->setDocument($document);

        $this->correctionManager->create($subject, $correction);

        // Vérification de l'existence de la nouvelle correction
        $id = $correction->getId();

        $correction = $this->correctionManager->get($id);

        $this->assertNotNull($correction);
        $this->assertEquals($name, $correction->getName());

        unlink($this->documentsDirectory . '/' . $document);
    }

    public function testUpdate()
    {
        // Mise à jour d'une correction
        $id = 1;
        $name = 'Test correction';

        /** @var Correction $correction */
        $correction = $this->correctionManager->get($id);

        $correction->setName($name);

        $this->correctionManager->update($correction);

        // Vérification de la mise à jour
        $correction = $this->correctionManager->get($id);

        $this->assertEquals($name, $correction->getName());
    }

    public function testDelete()
    {
        // Suppression d'une correction
        $id = 1;

        /** @var Correction $correction */
        $correction = $this->correctionManager->get($id);

        $document = $correction->getDocument();
        $tmp = 'tmp.pdf';

        copy($this->documentsDirectory . '/' . $document, $this->documentsDirectory . '/' . $tmp);

        $this->correctionManager->delete($correction);

        // Vérification de la suppression
        $correction = $this->correctionManager->get($id);

        $this->assertNull($correction);
        $this->assertFileNotExists($this->documentsDirectory . '/' . $document);

        rename($this->documentsDirectory . '/' . $tmp, $this->documentsDirectory . '/' . $document);
    }
}
