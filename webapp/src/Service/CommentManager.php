<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager extends DataManager
{
    private $loggedUserManager;
    private $userManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggedUserManager $loggedUserManager,
        UserManager $userManager
    ) {
        parent::__construct($entityManager, Comment::class);

        $this->loggedUserManager = $loggedUserManager;
        $this->userManager = $userManager;
    }

    public function isOwnedByUser(Comment $comment, User $user)
    {
        return $comment->getOwner()->getId() === $user->getId();
    }

    public function isOwnedByLoggedUser(Comment $comment)
    {
        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        return $this->isOwnedByUser($comment, $user);
    }

    public function create(Subject $subject, Comment $comment)
    {
        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        $comment->setDate(new \DateTime());

        $subject->addComment($comment);
        $user->addComment($comment);

        $this->entityManager->persist($comment);
        $this->entityManager->persist($subject);
        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }

    public function update(Comment $comment)
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function delete(Comment $comment)
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }
}
