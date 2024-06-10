<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }
    #[Route('/inscription', name: 'auth_registration')]
    public function registration(Request $request): Response
    {
        $user = $this->getUser();

        if ($user) {
            return $this->redirectToRoute('home');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $this->userService->registerUser($user, $plainPassword);
            $this->addFlash(
                'success',
                'Un lien de confirmation vous a été envoyé par mail. Veuillez suivre ce lien pour activer votre compte.'
            );
            return $this->redirectToRoute('auth_login');
        }
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
