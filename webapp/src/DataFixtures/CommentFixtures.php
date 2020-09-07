<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public const COMMENT_1_REF = 'comment_1';
    public const COMMENT_2_REF = 'comment_2';
    public const COMMENT_3_REF = 'comment_3';

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

        $comment1 = new Comment();

        $comment1->setText('Un premier commentaire.');
        $comment1->setDate(new \DateTime());
        $comment1->setOwner($user);
        $comment1->setSubject($subject1);

        $manager->persist($comment1);

        $comment2 = new Comment();

        $comment2->setText('Un deuxième commentaire.');
        $comment2->setDate(new \DateTime());
        $comment2->setOwner($resp);
        $comment2->setSubject($subject1);

        $manager->persist($comment2);

        $comment3 = new Comment();

        $comment3->setText('Un troisième commentaire.');
        $comment3->setDate(new \DateTime());
        $comment3->setOwner($admin);
        $comment3->setSubject($subject1);

        $manager->persist($comment3);

        $manager->flush();

        $this->addReference(self::COMMENT_1_REF, $comment1);
        $this->addReference(self::COMMENT_2_REF, $comment2);
        $this->addReference(self::COMMENT_3_REF, $comment3);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            SubjectFixtures::class
        ];
    }
}
