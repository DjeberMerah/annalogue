<?php

namespace App\DataFixtures;

use App\Entity\Module;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ModuleFixtures extends Fixture implements DependentFixtureInterface
{
    public const MODULE_1_REF = 'module_1';
    public const MODULE_2_REF = 'module_2';
    public const MODULE_3_REF = 'module_3';
    public const MODULE_4_REF = 'module_4';
    public const MODULE_5_REF = 'module_5';

    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_BASIC_REF);
        /** @var User $resp */
        $resp = $this->getReference(UserFixtures::USER_RESP_REF);
        /** @var User $admin */
        $admin = $this->getReference(UserFixtures::USER_ADMIN_REF);

        $module1 = new Module();

        $module1->setName('Test Fonctionnel');
        $module1->setDate(new \DateTime());
        $module1->setOwner($resp);

        $manager->persist($module1);

        $module2 = new Module();

        $module2->setName('Ingénierie dirigée par les modèles');
        $module2->setDate(new \DateTime());
        $module2->setOwner($resp);

        $manager->persist($module2);

        $module3 = new Module();

        $module3->setName('Programmation d\'Architecture Multi-tiers');
        $module3->setDate(new \DateTime());
        $module3->setOwner($resp);

        $manager->persist($module3);

        $module4 = new Module();

        $module4->setName('Calculabilité et NP-Complétude');
        $module4->setDate(new \DateTime());
        $module4->setOwner($admin);

        $manager->persist($module4);

        $module5 = new Module();

        $module5->setName('Réseaux avancés');
        $module5->setDate(new \DateTime());
        $module5->setOwner($admin);

        $manager->persist($module5);

        $manager->flush();

        $this->addReference(self::MODULE_1_REF, $module1);
        $this->addReference(self::MODULE_2_REF, $module2);
        $this->addReference(self::MODULE_3_REF, $module3);
        $this->addReference(self::MODULE_4_REF, $module4);
        $this->addReference(self::MODULE_5_REF, $module5);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
