<?php

namespace App\Service;

use App\Entity\Module;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SubjectManager extends DataManager
{
    private $loggedUserManager;
    private $userManager;
    private $moduleManager;

    private $parameterBag;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggedUserManager $loggedUserManager,
        UserManager $userManager,
        ModuleManager $moduleManager,
        ParameterBagInterface $parameterBag
    ) {
        parent::__construct($entityManager, Subject::class);

        $this->loggedUserManager = $loggedUserManager;
        $this->userManager = $userManager;
        $this->moduleManager = $moduleManager;

        $this->parameterBag = $parameterBag;
    }

    public function isOwnedByUser(Subject $subject, User $user)
    {
        return $subject->getOwner()->getId() === $user->getId();
    }

    public function isOwnedByLoggedUser(Subject $subject)
    {
        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        return $this->isOwnedByUser($subject, $user);
    }

    public function loggedUserCanCreate(Module $module)
    {
        return $this->moduleManager->loggedUserCanHandle($module)
            or $this->moduleManager->isLoggedUserManager($module);
    }

    public function loggedUserCanHandle(Subject $subject)
    {
        return $this->moduleManager->loggedUserCanHandle($subject->getModule())
            or $this->isOwnedByLoggedUser($subject);
    }

    public function loggedUserCanInteract(Subject $subject)
    {
        return $this->moduleManager->loggedUserCanInteract($subject->getModule());
    }

    public function create(Module $module, Subject $subject)
    {
        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        $subject->setDate(new \DateTime());

        $module->addSubject($subject);
        $user->addSubject($subject);

        $this->entityManager->persist($subject);
        $this->entityManager->persist($module);
        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }

    public function update(Subject $subject)
    {
        $this->entityManager->persist($subject);
        $this->entityManager->flush();
    }

    public function delete(Subject $subject)
    {
        $directory = $this->parameterBag->get('documents_directory');
        $path = $directory . '/' . $subject->getDocument();

        if (file_exists($path)) {
            unlink($path);
        }

        $this->entityManager->remove($subject);
        $this->entityManager->flush();
    }
}
