<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use App\Service\InvoiceNumberGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/invoice')]
final class InvoiceController extends AbstractController
{
    #[Route(name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/new_invoice', name: 'app_invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, InvoiceNumberGenerator $generator): Response
    {
        $invoice = new Invoice();
        $invoice->setUser($this->getUser());

        // Set the number BEFORE creating the form
        $invoice->setInvoiceNumber(invoiceNumber: $generator->generateFor($this->getUser()));

        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invoice);
            $entityManager->flush();
            $this->addFlash('success', 'Invoice created successfully!');
            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }
        // 2. Handle Validation Errors (Turbo needs 422)
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please correct the errors below in the form.');

            return $this->render('invoice/new.html.twig', [
                'form' => $form,
            ], new Response(null, 422));
        }
        // 3. MANDATORY: The initial GET request (loading the page for the first time)
        return $this->render('invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        if (!$this->isGranted('INVOICE_VIEW', $invoice)) {
            throw $this->createAccessDeniedException('You cannot view this invoice.');
        }
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('INVOICE_EDIT', $invoice)) {
            throw $this->createAccessDeniedException('You cannot edit this invoice.');
        }
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Invoice modified successfully!');
            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please fix the errors below in the form.');

            return $this->render('invoice/edit.html.twig', [
                'invoice' => $invoice,
                'form' => $form,
            ], new Response(null, 422));
        }
        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('INVOICE_DELETE', $invoice)) {
            throw $this->createAccessDeniedException("You cannot delete this invoice.");
        }
        if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
            $this->addFlash('success', 'Invoice deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token. Invoice was not deleted.');
        }

        return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    }
}
