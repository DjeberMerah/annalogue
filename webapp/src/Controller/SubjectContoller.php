<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Module;
use App\Entity\Subject;
use App\Form\CommentType;
use App\Form\SubjectType;
use App\Service\CommentManager;
use App\Service\FileUploader;
use App\Service\LoggedUserManager;
use App\Service\ModuleManager;
use App\Service\SubjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubjectContoller extends AbstractController
{
    /**
     * @Route("/module/{idModule}/subject/create", name="app_subject_create")
     */
    public function create(
        Request $request,
        LoggedUserManager $loggedUserManager,
        ModuleManager $moduleManager,
        SubjectManager $subjectManager,
        FileUploader $fileUploader,
        int $idModule
    ): Response {
        /** @var Module $module */
        $module = $moduleManager->get($idModule);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        if (!$subjectManager->loggedUserCanCreate($module)) {
            return $this->redirectToRoute('app_home');
        }

        $subject = new Subject();

        $form = $this->createForm(SubjectType::class, $subject);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $documentFile */
            $documentFile = $form['document']->getData();

            $filename = $fileUploader->upload($documentFile, uniqid());

            if (!$filename) {
                // TODO: return error
            }

            $subject = $form->getData();

            $subject->setDocument($filename);

            $subjectManager->create($module, $subject);

            return $this->redirectToRoute('app_module_view', ['id' => $idModule]);
        }

        return $this->render('subject/create.html.twig', [
            'module' => $module,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/module/{idModule}/subject/{id}/update", name="app_subject_update")
     */
    public function update(
        Request $request,
        LoggedUserManager $loggedUserManager,
        ModuleManager $moduleManager,
        SubjectManager $subjectManager,
        int $idModule,
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

        if (!$subjectManager->loggedUserCanHandle($subject)) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(SubjectType::class, $subject, [
            'mode' => 'UPDATE'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subject = $form->getData();

            $subjectManager->update($subject);

            $this->addFlash('success', 'Le sujet ' . $subject->getName() . ' a bien été modifié.');

            return $this->redirectToRoute('app_module_view', ['id' => $idModule]);
        }

        return $this->render('subject/edit.html.twig', [
            'module' => $module,
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/module/{idModule}/subject/{id}", name="app_subject_view")
     */
    public function view(
        Request $request,
        LoggedUserManager $loggedUserManager,
        ModuleManager $moduleManager,
        SubjectManager $subjectManager,
        CommentManager $commentManager,
        int $idModule,
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

        if (!$subjectManager->loggedUserCanInteract($subject)) {
            return $this->redirectToRoute('app_home');
        }

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            $commentManager->create($subject, $comment);

            $this->addFlash('success', 'Le commentaire a bien été ajouté.');

            return $this->redirectToRoute('app_subject_view', ['idModule' => $idModule, 'id' => $id]);
        }

        return $this->render("subject/view.html.twig", [
            'module' => $module,
            'subject' => $subject,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/module/{idModule}/subject/{id}/delete", name="app_subject_delete")
     */
    public function delete(
        LoggedUserManager $loggedUserManager,
        ModuleManager $moduleManager,
        SubjectManager $subjectManager,
        int $idModule,
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

        if (!$subjectManager->loggedUserCanHandle($subject)) {
            return $this->redirectToRoute('app_home');
        }

        $subjectManager->delete($subject);

        $this->addFlash('success', 'Le sujet ' . $subject->getName() . ' a bien été supprimé.');

        return $this->redirectToRoute('app_module_view', ['id' => $idModule]);
    }
}
