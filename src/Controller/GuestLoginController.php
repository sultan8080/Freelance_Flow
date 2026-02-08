<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\DemoDataGenerator;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


class GuestLoginController extends AbstractController
{
    #[Route('/guest-login', name: 'app_guest_login')]
    public function index(
        EntityManagerInterface $entityManager,
        Security $security,
        UserPasswordHasherInterface $hasher,
        DemoDataGenerator $demoDataGenerator,
        TranslatorInterface $translator
    ): Response {

        // 1. Create Guest User
        $user = new User();
        $uniqueId = substr(uniqid(), -5);
        $user->setEmail("guest_{$uniqueId}@freelancelucid.com");

        // Fill fields
        $user->setFirstName("Guest");
        $user->setLastName("Recruiter");
        $user->setCompanyName("Recruitment Agency");
        $user->setAddress("123 Avenue des Champs-Élysées");
        $user->setPostCode("75008");
        $user->setCity("Paris");
        $user->setCountry("France");
        $randomSiret = str_pad((string)rand(1, 99999999999999), 14, '0', STR_PAD_LEFT);
        $user->setSiretNumber($randomSiret);


        $user->setRoles(['ROLE_USER', 'ROLE_GUEST']);
        $user->setPassword($hasher->hashPassword($user, 'guest123456'));

        $entityManager->persist($user);

        // Generate Demo Data
        $demoDataGenerator->generateFor($user);

        // Save to DB
        $entityManager->flush();

        // Login 
        $security->login($user, LoginFormAuthenticator::class, 'main');

        // 5. Redirect
                   $this->addFlash('success', $translator->trans('recruiter sucess login'));

        return $this->redirectToRoute('dashboard_freelancer');
    }
}
