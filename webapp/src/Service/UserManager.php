<?php

namespace App\Service;

use App\Entity\Module;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager extends DataManager
{
    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        parent::__construct($entityManager, User::class);

        $this->passwordEncoder = $passwordEncoder;
    }

    public function getSubscribedModules(User $user)
    {
        return $this->entityManager->getRepository(Module::class)->findBySubscribedUser($user->getId());
    }

    public function searchSubscribedModules(User $user, string $name)
    {
        return $this->entityManager->getRepository(Module::class)->searchSubscribedByName($name, $user->getId());
    }

    public function create(User $user)
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function delete(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
