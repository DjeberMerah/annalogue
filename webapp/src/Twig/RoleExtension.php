<?php

namespace App\Twig;

use App\Entity\Correction;
use App\Entity\Module;
use App\Entity\Subject;
use App\Entity\User;
use App\Service\CorrectionManager;
use App\Service\LoggedUserManager;
use App\Service\ModuleManager;
use App\Service\SubjectManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RoleExtension extends AbstractExtension
{
    private $loggedUserManager;
    private $moduleManager;
    private $subjectManager;
    private $correctionManager;

    public function __construct(
        LoggedUserManager $loggedUserManager,
        ModuleManager $moduleManager,
        SubjectManager $subjectManager,
        CorrectionManager $correctionManager
    ) {
        $this->loggedUserManager = $loggedUserManager;
        $this->moduleManager = $moduleManager;
        $this->subjectManager = $subjectManager;
        $this->correctionManager = $correctionManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('logged_user_can_create_module', [$this, 'loggedUserCanCreateModule']),
            new TwigFunction('logged_user_can_handle_module', [$this, 'loggedUserCanHandleModule']),
            new TwigFunction('logged_user_can_create_subject', [$this, 'loggedUserCanCreateSubject']),
            new TwigFunction('logged_user_can_handle_subject', [$this, 'loggedUserCanHandleSubject']),
            new TwigFunction('logged_user_can_create_correction', [$this, 'loggedUserCanCreateCorrection']),
            new TwigFunction('logged_user_can_handle_correction', [$this, 'loggedUserCanHandleCorrection']),
            new TwigFunction('is_user_manager', [$this, 'isUserManager'])
        ];
    }

    public function loggedUserCanCreateModule()
    {
        return $this->moduleManager->loggedUserCanCreate();
    }

    public function loggedUserCanHandleModule(Module $module)
    {
        return $this->moduleManager->loggedUserCanHandle($module);
    }

    public function loggedUserCanCreateSubject(Module $module)
    {
        return $this->subjectManager->loggedUserCanCreate($module);
    }

    public function loggedUserCanHandleSubject(Subject $subject)
    {
        return $this->subjectManager->loggedUserCanHandle($subject);
    }

    public function loggedUserCanCreateCorrection(Subject $subject)
    {
        return $this->correctionManager->loggedUserCanCreate($subject);
    }

    public function loggedUserCanHandleCorrection(Correction $correction)
    {
        return $this->correctionManager->loggedUserCanHandle($correction);
    }

    public function isUserManager(Module $module, User $user)
    {
        return $this->moduleManager->isUserManager($module, $user);
    }
}
