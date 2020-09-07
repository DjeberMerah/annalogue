<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\LoggedUserManager;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="app_users")
     */
    public function users(LoggedUserManager $loggedUserManager, UserManager $userManager): Response
    {
        if (!$loggedUserManager->isGranted(['ROLE_ADMIN'])) {
            return $this->redirectToRoute('app_home');
        }

        $users = $userManager->getAll();

        return $this->render("user/list.html.twig", [
            "users" => $users
        ]);
    }

    /**
     * @Route("/user/create", name="app_user_create")
     */
    public function create(Request $request, LoggedUserManager $loggedUserManager, UserManager $userManager): Response
    {
        if (!$loggedUserManager->isGranted(['ROLE_ADMIN'])) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $userManager->create($user);

            $this->addFlash('success', 'L\'utilisateur ' . $user->getName() . ' a bien été créé.');

            return $this->redirectToRoute('app_users');
        }

        return $this->render("user/create.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id}/update", name="app_user_update")
     */
    public function update(
        Request $request,
        LoggedUserManager $loggedUserManager,
        UserManager $userManager,
        int $id
    ): Response {
        if (!$loggedUserManager->isGranted(['ROLE_ADMIN'])) {
            return $this->redirectToRoute('app_home');
        }

        /** @var User $user */
        $user = $userManager->get($id);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'a pas été trouvé.');
        }

        $form = $this->createForm(UserType::class, $user, [
            'mode' => 'UPDATE'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $userManager->update($user);

            $this->addFlash('success', 'L\'utilisateur ' . $user->getName() . ' a bien été modifié.');

            return $this->redirectToRoute('app_users');
        }

        return $this->render("user/edit.html.twig", [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id}/delete", name="app_user_delete")
     */
    public function delete(LoggedUserManager $loggedUserManager, UserManager $userManager, int $id): Response
    {
        if (!$loggedUserManager->isGranted(['ROLE_ADMIN'])) {
            return $this->redirectToRoute('app_home');
        }

        $user = $userManager->get($id);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'a pas été trouvé.');
        }

        $userManager->delete($user);

        $this->addFlash('success', 'L\'utilisateur ' . $user->getName() . ' a bien été supprimé.');

        return $this->redirectToRoute('app_users');
    }
}
