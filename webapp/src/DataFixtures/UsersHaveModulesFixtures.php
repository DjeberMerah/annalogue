<?php

namespace App\DataFixtures;

use App\Entity\Module;
use App\Entity\User;
use App\Entity\UsersHaveModules;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UsersHaveModulesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_BASIC_REF);
        /** @var User $resp */
        $resp = $this->getReference(UserFixtures::USER_RESP_REF);
        /** @var User $admin */
        $admin = $this->getReference(UserFixtures::USER_ADMIN_REF);

        /** @var Module $module1 */
        $module1 = $this->getReference(ModuleFixtures::MODULE_1_REF);
        /** @var Module $module2 */
        $module2 = $this->getReference(ModuleFixtures::MODULE_2_REF);
        /** @var Module $module3 */
        $module3 = $this->getReference(ModuleFixtures::MODULE_3_REF);
        /** @var Module $module4 */
        $module4 = $this->getReference(ModuleFixtures::MODULE_4_REF);
        /** @var Module $module5 */
        $module5 = $this->getReference(ModuleFixtures::MODULE_5_REF);

        $usersHaveModules1 = new UsersHaveModules();

        $usersHaveModules1->setUser($user);
        $usersHaveModules1->setModule($module1);
        $usersHaveModules1->setFlag(true);

        $manager->persist($usersHaveModules1);

        $usersHaveModules2 = new UsersHaveModules();

        $usersHaveModules2->setUser($user);
        $usersHaveModules2->setModule($module2);
        $usersHaveModules2->setFlag(false);

        $manager->persist($usersHaveModules2);

        $usersHaveModules3 = new UsersHaveModules();

        $usersHaveModules3->setUser($user);
        $usersHaveModules3->setModule($module3);
        $usersHaveModules3->setFlag(false);

        $manager->persist($usersHaveModules3);

        $usersHaveModules4 = new UsersHaveModules();

        $usersHaveModules4->setUser($admin);
        $usersHaveModules4->setModule($module1);
        $usersHaveModules4->setFlag(false);

        $manager->persist($usersHaveModules4);

        $usersHaveModules5 = new UsersHaveModules();

        $usersHaveModules5->setUser($admin);
        $usersHaveModules5->setModule($module4);
        $usersHaveModules5->setFlag(false);

        $manager->persist($usersHaveModules5);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ModuleFixtures::class
        ];
    }
}
