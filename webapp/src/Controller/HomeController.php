<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\LoggedUserManager;
use App\Service\ModuleManager;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(
        LoggedUserManager $loggedUserManager,
        UserManager $userManager,
        ModuleManager $moduleManager
    ): Response {
        if (!$loggedUserManager->isGranted(['IS_AUTHENTICATED_REMEMBERED'])) {
            return $this->redirectToRoute('app_login');
        }

        if ($loggedUserManager->isGranted(['ROLE_ADMIN'])) {
            $modules = $moduleManager->getAll();
        } else {
            /** @var User $user */
            $user = $loggedUserManager->getUser();

            $modules = $userManager->getSubscribedModules($user);
        }

        return $this->render('home/index.html.twig', [
            'modules' => $modules
        ]);
    }

    /**
     * @Route("/search", name="app_search")
     */
    public function results(
        Request $request,
        LoggedUserManager $loggedUserManager,
        UserManager $userManager,
        ModuleManager $moduleManager
    ): Response {
        if (!$loggedUserManager->isGranted(['IS_AUTHENTICATED_REMEMBERED'])) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->getMethod() !== 'POST') {
            return $this->redirectToRoute('app_home');
        }

        $form = $request->get('form');

        $term = $form['term'];

        if ($loggedUserManager->isGranted(['ROLE_ADMIN'])) {
            $modules = $moduleManager->searchAll($term);
        } else {
            /** @var User $user */
            $user = $loggedUserManager->getUser();

            $modules = $userManager->searchSubscribedModules($user, $term);
        }

        return $this->render('home/results.html.twig', [
            'term' => $term,
            'modules' => $modules
        ]);
    }

    public function search(): Response
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_search'))
            ->add('term', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ex : Test fonctionnel'
                ],
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher'
            ])
            ->getForm();

        return $this->render('home/search.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
