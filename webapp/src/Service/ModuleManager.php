<?php

namespace App\Service;

use App\Entity\Module;
use App\Entity\User;
use App\Entity\UsersHaveModules;
use Doctrine\ORM\EntityManagerInterface;

class ModuleManager extends DataManager
{
    private $loggedUserManager;
    private $userManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggedUserManager $loggedUserManager,
        UserManager $userManager
    ) {
        parent::__construct($entityManager, Module::class);

        $this->loggedUserManager = $loggedUserManager;
        $this->userManager = $userManager;
    }

    public function isOwnedByUser(Module $module, User $user)
    {
        return $module->getOwner()->getId() === $user->getId();
    }

    public function isOwnedByLoggedUser(Module $module)
    {
        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        return $this->isOwnedByUser($module, $user);
    }

    public function isUserSubscribed(Module $module, User $user)
    {
        return $this->repository->isSubscribed($module->getId(), $user->getId());
    }

    public function isLoggedUserSubscribed(Module $module)
    {
        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        return $this->isUserSubscribed($module, $user);
    }

    public function isUserManager(Module $module, User $user)
    {
        return $this->repository->isManager($module->getId(), $user->getId());
    }

    public function isLoggedUserManager(Module $module)
    {
        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        return $this->isUserManager($module, $user);
    }

    public function loggedUserCanCreate()
    {
        return $this->loggedUserManager->isGranted(['ROLE_ADMIN', 'ROLE_RESP']);
    }

    public function loggedUserCanHandle(Module $module)
    {
        return $this->loggedUserManager->isGranted(['ROLE_ADMIN'])
            or ($this->loggedUserManager->isGranted(['ROLE_RESP'])
                and $this->isOwnedByLoggedUser($module));
    }

    public function loggedUserCanInteract(Module $module)
    {
        return $this->loggedUserManager->isGranted(['ROLE_ADMIN'])
            or $this->isOwnedByLoggedUser($module)
            or $this->isLoggedUserSubscribed($module);
    }

    public function getSubscribedUsers(Module $module)
    {
        return $this->entityManager->getRepository(User::class)->findBySubscribedModule($module->getId());
    }

    public function getNonSubscribedUsers(Module $module)
    {
        return $this->entityManager->getRepository(User::class)->findByNonSubscribedModule($module->getId());
    }

    public function searchAll(string $name)
    {
        return $this->repository->searchByName($name);
    }

    public function subscribe(Module $module, array $map)
    {
        foreach ($map as $entry) {
            /** @var User $user */
            $user = $entry['user'];

            /** @var bool $flag */
            $flag = $entry['flag'];

            $userHaveModule = new UsersHaveModules();

            $userHaveModule->setModule($module);
            $userHaveModule->setUser($user);
            $userHaveModule->setFlag($flag);

            $this->entityManager->persist($userHaveModule);
        }

        $this->entityManager->flush();
    }

    public function unsubscribe(Module $module, User $user)
    {
        $userHaveModule = $this->entityManager->getRepository(UsersHaveModules::class)->findOneBy([
            'module' => $module,
            'user' => $user
        ]);

        if ($userHaveModule) {
            $this->entityManager->remove($userHaveModule);
            $this->entityManager->flush();
        }
    }

    public function setUserManager(Module $module, User $user)
    {
        $userHaveModule = $this->entityManager->getRepository(UsersHaveModules::class)->findOneBy([
            'module' => $module,
            'user' => $user
        ]);

        if ($userHaveModule) {
            $userHaveModule->setFlag(true);

            $this->entityManager->persist($userHaveModule);
            $this->entityManager->flush();
        }
    }

    public function unsetUserManager(Module $module, User $user)
    {
        $userHaveModule = $this->entityManager->getRepository(UsersHaveModules::class)->findOneBy([
            'module' => $module,
            'user' => $user
        ]);

        if ($userHaveModule) {
            $userHaveModule->setFlag(false);

            $this->entityManager->persist($userHaveModule);
            $this->entityManager->flush();
        }
    }

    public function create(Module $module)
    {
        /** @var User $user */
        $user = $this->loggedUserManager->getUser();

        $module->setDate(new \DateTime());

        $user->addModule($module);

        $this->entityManager->persist($module);
        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }

    public function update(Module $module)
    {
        $this->entityManager->persist($module);
        $this->entityManager->flush();
    }

    public function delete(Module $module)
    {
        $this->entityManager->remove($module);
        $this->entityManager->flush();
    }
}
