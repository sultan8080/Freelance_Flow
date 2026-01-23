<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/client')]
final class ClientController extends AbstractController
{
    #[Route(name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig');
        // return $this->render('client/index.html.twig', [
        //     'clients' => $clientRepository->findAll(),
        // ]);
    }
    /*
    public function index(ClientRepository $clientRepository): Response
    {
        // Only show clients linked to THIS user
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }
 */

    #[Route('/new_client', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $client->setUser($this->getUser());

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        // SUCCESS
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client created successfully!');

            //turbo stream response
            if ($request->headers->has('Turbo-Frame')) {
                return new Response($this->renderView('dashboard/_flash_success.stream.html.twig'), 200, ['Content-Type' => TurboBundle::STREAM_MEDIA_TYPE]);
            }

            return $this->redirectToRoute('app_client_index');
        }

        // INVALID
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please correct the errors below in the form.');

            return $this->render(
                'client/new.html.twig',
                [
                    'client' => $client,
                    'form' => $form,
                ],
                new Response(null, Response::HTTP_UNPROCESSABLE_ENTITY)
            );
        }

        // INITIAL LOAD
        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }





    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        // SECURITY CHECK: Ensure this client belongs to the logged-in user
        if ($client->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot view this client\'s information.');
        }

        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        // SECURITY CHECK: Ensure this client belongs to the logged-in user
        if ($client->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot edit this client.');
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Client profile modified successfully!');

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please fix the errors below in the form.');

            // Turbo expects 422 to re-render the form inside the frame
            return $this->render('client/edit.html.twig', [
                'client' => $client,
                'form' => $form,
            ], new Response(null, Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        // 1. SECURITY CHECK: Ensure this client belongs to the logged-in user
        if ($client->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot delete this client.');
        }

        // 2. CSRF TOKEN CHECK: Prevents cross-site request forgery
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client profile deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid security token. Deletion cancelled.');
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
