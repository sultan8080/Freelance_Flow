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
    }

    #[Route('/new_client', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);

        $emptyForm = clone $form;
        $form->handleRequest($request);

        // 1. Handle invalid form with Turbo Stream
        if ($form->isSubmitted() && !$form->isValid()) {
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client/new.html.twig', 'error_stream', [
                    'form' => $form,
                ]);
            }
        }

        // 2. Handle valid form
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client created successfully!');

            // Turbo Stream success
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('client/new.html.twig', 'success_stream', [
                    'form' => $emptyForm,
                    'client' => $client,
                ]);
            }

            // Normal redirect (VERY IMPORTANT)
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        // 3. First page load
        return $this->render('client/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
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
        if ($client->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot edit this client.');
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Client profile modified successfully!');

            return $this->redirectToRoute('app_client_index');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please fix the errors below in the form.');

            return $this->render('client/edit.html.twig', [
                'client' => $client,
                'form' => $form,
            ], new Response(null, 422));
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($client->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot delete this client.');
        }

        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client profile deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid security token. Deletion cancelled.');
        }

        return $this->redirectToRoute('app_client_index');
    }
}
