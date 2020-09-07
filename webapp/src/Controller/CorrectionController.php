<?php

namespace App\Controller;

use App\Entity\Correction;
use App\Entity\Module;
use App\Entity\Subject;
use App\Form\CorrectionType;
use App\Service\CorrectionManager;
use App\Service\FileUploader;
use App\Service\LoggedUserManager;
use App\Service\ModuleManager;
use App\Service\SubjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CorrectionController extends AbstractController
{
    /**
     * @Route("/module/{idModule}/subject/{idSubject}/correction/create", name="app_correction_create")
     */
    public function create(
        Request $request,
        LoggedUserManager $loggedUserManager,
        ModuleManager $moduleManager,
        SubjectManager $subjectManager,
        CorrectionManager $correctionManager,
        FileUploader $fileUploader,
        int $idModule,
        int $idSubject
    ): Response {
        /** @var Module $module */
        $module = $moduleManager->get($idModule);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        /** @var Subject $subject */
        $subject = $subjectManager->get($idSubject);

        if (!$subject) {
            throw $this->createNotFoundException('Le sujet n\'a pas été trouvé.');
        }

        if (!$correctionManager->loggedUserCanCreate($subject)) {
            return $this->redirectToRoute('app_home');
        }

        $correction = new Correction();

        $form = $this->createForm(CorrectionType::class, $correction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $documentFile */
            $documentFile = $form['document']->getData();

            $filename = $fileUploader->upload($documentFile, uniqid());

            if (!$filename) {
                // TODO: something went wrong; return error
            }

            $correction = $form->getData();

            $correction->setDocument($filename);

            $correctionManager->create($subject, $correction);

            return $this->redirectToRoute('app_subject_view', ['idModule' => $idModule, 'id' => $idSubject]);
        }

        return $this->render('correction/create.html.twig', [
            'module' => $module,
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/module/{idModule}/subject/{idSubject}/correction/{id}", name="app_correction_view")
     */
    public function view(
        LoggedUserManager $loggedUserManager,
        ModuleManager $moduleManager,
        SubjectManager $subjectManager,
        CorrectionManager $correctionManager,
        FileUploader $fileUploader,
        int $idModule,
        int $idSubject,
        int $id
    ): Response {
        /** @var Module $module */
        $module = $moduleManager->get($idModule);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        /** @var Subject $subject */
        $subject = $subjectManager->get($id);

        if (!$subject) {
            throw $this->createNotFoundException('Le sujet n\'a pas été trouvé.');
        }

        /** @var Correction $correction */
        $correction = $correctionManager->get($id);

        if (!$correction) {
            throw $this->createNotFoundException('La correction n\'a pas été trouvée.');
        }

        return $this->render("correction/view.html.twig", [
            'module' => $module,
            'subject' => $subject,
            'correction' => $correction
        ]);
    }

}
