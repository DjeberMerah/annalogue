<?php

namespace App\Service;

use App\Entity\Correction;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CorrectionManager extends DataManager
{
    private $loggedUserManager;
    private $userManager;
    private $subjectManager;

    private $parameterBag;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggedUserManager $loggedUserManager,
        UserManager $userManager,
        SubjectManager $subjectManager,
        ParameterBagInterface $parameterBag
    ) {
        parent::__construct($entityManager, Correction::class);

        $this->loggedUserManager = $loggedUserManager;
        $this->userManager = $userManager;
        $this->subjectManager = $subjectManager;

        $this->parameterBag = $parameterBag;
    }

    public function isOwnedByUser(Correction $correction, User $user)
    {
        return $correction->getOwner()->getId() === $user->getId();
    }

    public function isOwnedByLoggedUser(Correction $correction)
    {
        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        return $this->isOwnedByUser($correction, $user);
    }

    public function loggedUserCanCreate(Subject $subject)
    {
        return $this->subjectManager->loggedUserCanInteract($subject);
    }

    public function loggedUserCanHandle(Correction $correction)
    {
        return $this->subjectManager->loggedUserCanHandle($correction->getSubject())
            or $this->isOwnedByLoggedUser($correction);
    }

    public function loggedUserCanInteract(Correction $correction)
    {
        $this->subjectManager->loggedUserCanInteract($correction->getSubject());
    }

    public function create(Subject $subject, Correction $correction)
    {
        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        $correction->setDate(new \DateTime());

        $subject->addCorrection($correction);
        $user->addCorrection($correction);

        $this->entityManager->persist($correction);
        $this->entityManager->persist($subject);
        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }

    public function update(Correction $correction)
    {
        $this->entityManager->persist($correction);
        $this->entityManager->flush();
    }

    public function delete(Correction $correction)
    {
        $directory = $this->parameterBag->get('documents_directory');
        $path = $directory . '/' . $correction->getDocument();

        if (file_exists($path)) {
            unlink($path);
        }

        $this->entityManager->remove($correction);
        $this->entityManager->flush();
    }
}
