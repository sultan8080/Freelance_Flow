<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class SettingsController extends AbstractController
{
    #[Route('/profile', name: 'app_profile_show')]
    public function show(): Response
    {
        return $this->render('settings/show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'Profile updated successfully!');

                return $this->redirectToRoute('app_profile_show');
            }
        }
        $statusCode = ($form->isSubmitted() && !$form->isValid()) ? 422 : 200;
        return $this->render('settings/profile.html.twig', [
            'userForm' => $form->createView(),
        ], new Response(null, $statusCode));
    }
}
