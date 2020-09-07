<?php

namespace App\Service;

use Symfony\Component\Security\Core\Security;

class LoggedUserManager
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getUser()
    {
        return $this->security->getUser();
    }

    public function isGranted($roles)
    {
        return $this->security->isGranted($roles);
    }
}
