<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const USER_BASIC_REF = 'user_basic';
    public const USER_RESP_REF = 'user_resp';
    public const USER_ADMIN_REF = 'user_admin';

    private $passwordEncoder;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $basic = new User();

        $basic->setMail('user@univ-fcomte.fr');
        $basic->setName('Basic user');
        $basic->setPassword($this->passwordEncoder->encodePassword($basic, '1234'));
        $basic->setRoles(['ROLE_USER']);

        $manager->persist($basic);

        $resp = new User();

        $resp->setMail('resp@univ-fcomte.fr');
        $resp->setName('Responsable');
        $resp->setPassword($this->passwordEncoder->encodePassword($resp, '1234'));
        $resp->setRoles(['ROLE_USER', 'ROLE_RESP']);

        $manager->persist($resp);

        $admin = new User();

        $admin->setMail('admin@univ-fcomte.fr');
        $admin->setName('Admin');
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, '1234'));
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();

        $this->addReference(self::USER_BASIC_REF, $basic);
        $this->addReference(self::USER_RESP_REF, $resp);
        $this->addReference(self::USER_ADMIN_REF, $admin);
    }
}
