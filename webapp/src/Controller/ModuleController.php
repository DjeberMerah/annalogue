<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\User;
use App\Form\ModuleType;
use App\Service\LoggedUserManager;
use App\Service\ModuleManager;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModuleController extends AbstractController
{
    /**
     * @Route("/module/create", name="app_module_create")
     */
    public function create(
        Request $request,
        LoggedUserManager $loggedUserManager,
        ModuleManager $moduleManager
    ): Response {
        if (!$moduleManager->loggedUserCanCreate()) {
            return $this->redirectToRoute('app_home');
        }

        $module = new Module();

        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData();

            $moduleManager->create($module);

            $this->addFlash('success', 'Le module ' . $module->getName() . ' a bien été créé.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('module/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/module/{id}", name="app_module_view")
     */
    public function view(LoggedUserManager $loggedUserManager, ModuleManager $moduleManager, int $id): Response
    {
        /** @var Module $module */
        $module = $moduleManager->get($id);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        if (!$moduleManager->loggedUserCanInteract($module)) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render("module/view.html.twig", [
            "module" => $module
        ]);
    }

    /**
     * @Route("/module/{id}/update", name="app_module_update")
     */
    public function update(
        Request $request,
        LoggedUserManager $loggedUserManager,
        ModuleManager $moduleManager,
        int $id
    ): Response {
        /** @var Module $module */
        $module = $moduleManager->get($id);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        if (!$moduleManager->loggedUserCanHandle($module)) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(ModuleType::class, $module, [
            'mode' => 'UPDATE'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData();

            $moduleManager->update($module);

            $this->addFlash('success', 'Le module ' . $module->getName() . ' a bien été modifié.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('module/edit.html.twig', [
            'module' => $module,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/module/{id}/delete", name="app_module_delete")
     */
    public function delete(LoggedUserManager $loggedUserManager, ModuleManager $moduleManager, int $id): Response
    {
        /** @var Module $module */
        $module = $moduleManager->get($id);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        if (!$moduleManager->loggedUserCanHandle($module)) {
            return $this->redirectToRoute('app_home');
        }

        $moduleManager->delete($module);

        $this->addFlash('success', 'Le module ' . $module->getName() . ' a bien été supprimé.');

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/module/{id}/users", name="app_module_users")
     */
    public function users(LoggedUserManager $loggedUserManager, ModuleManager $moduleManager, int $id): Response
    {
        /** @var Module $module */
        $module = $moduleManager->get($id);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        if (!$moduleManager->loggedUserCanHandle($module)) {
            return $this->redirectToRoute('app_home');
        }

        $users = $moduleManager->getSubscribedUsers($module);

        $nonSubscribedUsers = $moduleManager->getNonSubscribedUsers($module);

        return $this->render('module/users.html.twig', [
            'module' => $module,
            'users' => $users,
            'non_subscribed_users' => $nonSubscribedUsers
        ]);
    }

    /**
     * @Route("/module/{id}/users/subscribe", name="app_users_subscribe" )
     */
    public function subscribe(
        Request $request,
        LoggedUserManager $loggedUserManager,
        UserManager $userManager,
        ModuleManager $moduleManager,
        int $id
    ): Response {
        /** @var Module $module */
        $module = $moduleManager->get($id);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        if ($request->request->count() > 0) {
            $userIds = $request->request->get('user');

            $map = [];

            foreach ($userIds as $userId) {
                /** @var User $user */
                $user = $userManager->get($userId);

                $map[] = [
                    'user' => $user,
                    'flag' => false
                ];
            }

            $moduleManager->subscribe($module, $map);

            $this->addFlash('success', 'Les utilisateurs ont bien été inscrits au module ' . $module->getName() . '.');
        }

        return $this->redirectToRoute('app_module_users', [
            'id' => $id
        ]);
    }

    /**
     * @Route("/module/{id}/user/{idUser}/unsubscribe", name="app_module_unsubscribe")
     */
    public function unsubscribe(
        LoggedUserManager $loggedUserManager,
        UserManager $userManager,
        ModuleManager $moduleManager,
        int $id,
        int $idUser
    ): Response {
        /** @var Module $module */
        $module = $moduleManager->get($id);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        if (!$moduleManager->loggedUserCanHandle($module)) {
            return $this->redirectToRoute('app_home');
        }

        /** @var User $user */
        $user = $userManager->get($idUser);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'a pas été trouvé.');
        }

        $moduleManager->unsubscribe($module, $user);

        $this->addFlash('success',
            'L\'utilisateur ' . $user->getName() . ' a bien été désinscrit du module ' . $module->getName() . '.');

        return $this->redirectToRoute('app_module_users', ['id' => $id]);
    }

    /**
     * @Route("/module/{id}/user/{idUser}/manager/set", name="app_module_manager_set")
     */
    public function setManager(
        LoggedUserManager $loggedUserManager,
        UserManager $userManager,
        ModuleManager $moduleManager,
        int $id,
        int $idUser
    ): Response {
        /** @var Module $module */
        $module = $moduleManager->get($id);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        if (!$moduleManager->loggedUserCanHandle($module)) {
            return $this->redirectToRoute('app_home');
        }

        /** @var User $user */
        $user = $userManager->get($idUser);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'a pas été trouvé.');
        }

        $moduleManager->setUserManager($module, $user);

        $this->addFlash('success',
            'L\'utilisateur ' . $user->getName() . ' est maintenant un intervenant du module ' . $module->getName() . '.');

        return $this->redirectToRoute('app_module_users', ['id' => $id]);
    }

    /**
     * @Route("/module/{id}/user/{idUser}/manager/unset", name="app_module_manager_unset")
     */
    public function unsetManager(
        LoggedUserManager $loggedUserManager,
        UserManager $userManager,
        ModuleManager $moduleManager,
        int $id,
        int $idUser
    ): Response {
        /** @var Module $module */
        $module = $moduleManager->get($id);

        if (!$module) {
            throw $this->createNotFoundException('Le module n\'a pas été trouvé.');
        }

        if (!$moduleManager->loggedUserCanHandle($module)) {
            return $this->redirectToRoute('app_home');
        }

        /** @var User $user */
        $user = $userManager->get($idUser);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'a pas été trouvé.');
        }

        $moduleManager->unsetUserManager($module, $user);

        $this->addFlash('success',
            'L\'utilisateur ' . $user->getName() . ' n\'est plus un intervenant du module ' . $module->getName() . '.');

        return $this->redirectToRoute('app_module_users', ['id' => $id]);
    }
}
