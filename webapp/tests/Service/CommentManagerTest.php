<?php

namespace App\Tests\Service;

use App\Entity\Comment;
use App\Entity\Subject;
use App\Entity\User;
use App\Service\CommentManager;
use App\Service\SubjectManager;
use App\Tests\LoggedUserTestCase;

class CommentManagerTest extends LoggedUserTestCase
{
    /** @var SubjectManager $subjectManager */
    private $subjectManager;
    /** @var CommentManager $commentManager */
    private $commentManager;

    protected function setUp()
    {
        parent::setUp();

        $this->subjectManager = self::$container->get(SubjectManager::class);
        $this->commentManager = self::$container->get(CommentManager::class);
    }

    public function testGetAll()
    {
        $comments = $this->commentManager->getAll();

        $this->assertCount(3, $comments);
    }

    public function testGet()
    {
        $id = 1;

        /** @var Comment $comment */
        $comment = $this->commentManager->get($id);

        $this->assertEquals($id, $comment->getId());
        $this->assertEquals('Un premier commentaire.', $comment->getText());
    }

    public function testIsOwnedByUser()
    {
        /** @var User $user */
        $user = $this->userManager->get(1);
        /** @var User $admin */
        $admin = $this->userManager->get(3);
        /** @var Comment $comment */
        $comment = $this->commentManager->get(1);

        $this->assertTrue($this->commentManager->isOwnedByUser($comment, $user));
        $this->assertFalse($this->commentManager->isOwnedByUser($comment, $admin));
    }

    public function testIsOwnedByLoggedUser()
    {
        $this->login(1);

        /** @var Comment $comment1 */
        $comment1 = $this->commentManager->get(1);
        /** @var Comment $comment2 */
        $comment2 = $this->commentManager->get(2);

        $this->assertTrue($this->commentManager->isOwnedByLoggedUser($comment1));
        $this->assertFalse($this->commentManager->isOwnedByLoggedUser($comment2));
    }

    public function testCreate()
    {
        $this->login(1);

        /** @var Subject $subject */
        $subject = $this->subjectManager->get(1);

        // Création d'un nouveau commentaire
        $text = 'Commentaire de test';

        $comment = new Comment();

        $comment->setText($text);

        $this->commentManager->create($subject, $comment);

        // Vérification de l'existence du nouveau commentaire
        $id = $comment->getId();

        $comment = $this->commentManager->get($id);

        $this->assertNotNull($comment);
        $this->assertEquals($text, $comment->getText());
    }

    public function testUpdate()
    {
        // Mise à jour d'un commentaire
        $id = 1;
        $text = 'Commentaire de test';

        /** @var Comment $comment */
        $comment = $this->commentManager->get($id);

        $comment->setText($text);

        $this->commentManager->update($comment);

        // Vérification de la mise à jour
        $comment = $this->commentManager->get($id);

        $this->assertEquals($text, $comment->getText());
    }

    public function testDelete()
    {
        // Suppression d'un commentaire
        $id = 1;

        /** @var Comment $comment */
        $comment = $this->commentManager->get($id);

        $this->commentManager->delete($comment);

        // Vérification de la suppression
        $comment = $this->commentManager->get($id);

        $this->assertNull($comment);
    }
}
