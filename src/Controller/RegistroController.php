<?php

use Symfony\Component\Routing\Annotation\Route;

class RegistroController extends AbstractController
{

    #[Route('/registro', name: 'app_registro', methods: ["POST"])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $user = new Usuario();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_homepage');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}