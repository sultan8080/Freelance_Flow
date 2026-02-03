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
    #[Route('/profile', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        // 1. Get the current logged-in user
        $user = $this->getUser();

        // 2. Create the form using the UserType (for Freelancer) we built
        $form = $this->createForm(UserType::class, $user);

        // 3. Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // SUCCESS CASE
                $entityManager->flush();
                $this->addFlash('success', 'Profile updated successfully!');

                return $this->redirectToRoute('app_profile_edit');
            } else {
                // ERROR CASE
                $this->addFlash('error', 'There was an error updating your profile. Please check the fields below.');
            }
        }
        return $this->render('settings/profile.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
}
