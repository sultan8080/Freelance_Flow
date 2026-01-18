<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SettingsController extends AbstractController
{
    #[Route('/settings/profile', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        // 1. Get the current logged-in user
        $user = $this->getUser();

        // 2. Create the form using the UserType we built
        $form = $this->createForm(UserType::class, $user);

        // 3. Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 4. Save the changes (SIRET, Address, etc.) to the database
            $entityManager->flush();

            // 5. Success message
            $this->addFlash('success', 'Profile updated successfully!');

            return $this->redirectToRoute('app_profile_edit');
        }

        return $this->render('settings/profile.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
}