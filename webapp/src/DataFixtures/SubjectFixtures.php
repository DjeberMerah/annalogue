<?php

namespace App\DataFixtures;

use App\Entity\Module;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;

class SubjectFixtures extends Fixture implements DependentFixtureInterface
{
    public const SUBJECT_1_REF = 'subject_1';
    public const SUBJECT_2_REF = 'subject_2';
    public const SUBJECT_3_REF = 'subject_3';

    private $container;

    private $directory;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->directory = $this->container->getParameter('documents_directory');
    }

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

        $subject1 = new Subject();

        $subject1->setName('Tests unitaires web');
        $subject1->setDescription('Bien écrire les tests de son composant logiciel PHP.');
        $subject1->setDocument('subject-1.pdf');
        $subject1->setType('TP');
        $subject1->setDate(new \DateTime());
        $subject1->setOwner($resp);
        $subject1->setModule($module1);

        $manager->persist($subject1);

        $subject2 = new Subject();

        $subject2->setName('La carotte connectée');
        $subject2->setDocument('subject-2.pdf');
        $subject2->setType('TD');
        $subject2->setDate(new \DateTime());
        $subject2->setOwner($resp);
        $subject2->setModule($module3);

        $manager->persist($subject2);

        $subject3 = new Subject();

        $subject3->setName('Pocket-Market');
        $subject3->setDocument('subject-3.pdf');
        $subject3->setType('TD');
        $subject3->setDate(new \DateTime());
        $subject3->setOwner($resp);
        $subject3->setModule($module3);

        $manager->persist($subject3);

        $manager->flush();

        $this->addReference(self::SUBJECT_1_REF, $subject1);
        $this->addReference(self::SUBJECT_2_REF, $subject2);
        $this->addReference(self::SUBJECT_3_REF, $subject3);

        if (!file_exists($this->directory)) {
            mkdir($this->directory, 0777, true);
        }

        for ($i = 1; $i < 4; $i++) {
            copy('src/DataFixtures/uploads/documents/subject-' . $i . '.pdf',
                $this->directory . '/subject-' . $i . '.pdf');
        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ModuleFixtures::class
        ];
    }
}
