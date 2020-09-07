<?php

namespace App\DataFixtures;

use App\Entity\Correction;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CorrectionFixtures extends Fixture implements DependentFixtureInterface
{
    public const CORRECTION_1_REF = 'correction_1';
    public const CORRECTION_2_REF = 'correction_2';
    public const CORRECTION_3_REF = 'correction_3';

    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_BASIC_REF);
        /** @var User $resp */
        $resp = $this->getReference(UserFixtures::USER_RESP_REF);
        /** @var User $admin */
        $admin = $this->getReference(UserFixtures::USER_ADMIN_REF);

        /** @var Subject $subject1 */
        $subject1 = $this->getReference(SubjectFixtures::SUBJECT_1_REF);
        /** @var Subject $subject2 */
        $subject2 = $this->getReference(SubjectFixtures::SUBJECT_2_REF);
        /** @var Subject $subject3 */
        $subject3 = $this->getReference(SubjectFixtures::SUBJECT_3_REF);

        $correction1 = new Correction();

        $correction1->setName('Correction 1');
        $correction1->setDescription('Première correction.');
        $correction1->setDocument('subject-1.pdf');
        $correction1->setDate(new \DateTime());
        $correction1->setOwner($resp);
        $correction1->setSubject($subject1);

        $manager->persist($correction1);

        $correction2 = new Correction();

        $correction2->setName('Correction 2');
        $correction2->setDescription('Deuxième correction.');
        $correction2->setDocument('subject-2.pdf');
        $correction2->setDate(new \DateTime());
        $correction2->setOwner($resp);
        $correction2->setSubject($subject1);

        $manager->persist($correction2);

        $correction3 = new Correction();

        $correction3->setName('Correction 3');
        $correction3->setDescription('Troisième correction.');
        $correction3->setDocument('subject-3.pdf');
        $correction3->setDate(new \DateTime());
        $correction3->setOwner($resp);
        $correction3->setSubject($subject1);

        $manager->persist($correction3);

        $manager->flush();

        $this->addReference(self::CORRECTION_1_REF, $correction1);
        $this->addReference(self::CORRECTION_2_REF, $correction2);
        $this->addReference(self::CORRECTION_3_REF, $correction3);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            SubjectFixtures::class
        ];
    }
}
