<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use App\Service\InvoiceCalculator;
use App\Service\InvoiceNumberGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/invoice')]
final class InvoiceController extends AbstractController
{
    #[Route(name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('invoice/index.html.twig', [
            // Before: 'invoices' => $invoiceRepository->findBy(['user' => $this->getUser()], ['id' => 'DESC']),
            // After: 1 query instead of N+1
            'invoices' => $invoiceRepository->findAllForUserWithRelations($this->getUser()),
        ]);
    }

    #[Route('/new_invoice', name: 'app_invoice_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        InvoiceCalculator $calculator,
        InvoiceNumberGenerator $generator
    ): Response {
        $invoice = new Invoice();
        $invoice->setUser($this->getUser());

        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //  TRANSITION LOGIC 
            if ($invoice->getStatus() !== 'DRAFT') {

                $now = new \DateTimeImmutable();

                // 1. Generate Number
                if (!$invoice->getInvoiceNumber()) {
                    $invoice->setInvoiceNumber($generator->generateFor($this->getUser()));
                }
                // 2. Freeze Data
                $invoice->collectSnapshot();

                // 3. Set Sent Date (the date it will be sent)
                if (!$invoice->getSentAt()) {
                    $invoice->setSentAt($now);
                }

                // 4. Set Due Date (Sent date + 30 Days)
                if (!$invoice->getDueDate()) {
                    $invoice->setDueDate($now->modify('+30 days'));
                }

                // 5. Handle Payment
                if ($invoice->getStatus() === 'PAID') {
                    $invoice->setPaidAt($now);
                }
            }
            // calculate totals of the invoice
            $calculator->calculateInvoice($invoice);
            $entityManager->persist($invoice);
            $entityManager->flush();
            $this->addFlash('success', 'Invoice created successfully!');
            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Invoice $invoice,
        EntityManagerInterface $entityManager,
        InvoiceCalculator $calculator,
        InvoiceNumberGenerator $generator
    ): Response {
        // Security Check
        if ($invoice->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // --- TRANSITION LOGIC ---
            if ($invoice->getStatus() !== 'DRAFT') {

                $now = new \DateTimeImmutable();
                // Generate Number if missing
                if (!$invoice->getInvoiceNumber()) {
                    $invoice->setInvoiceNumber($generator->generateFor($this->getUser()));
                    $invoice->collectSnapshot();
                    $invoice->setSentAt($now);

                    // Auto-set Due Date (+30 days)
                    if (!$invoice->getDueDate()) {
                        $invoice->setDueDate($now->modify('+30 days'));
                    }

                    // Initial Calculation
                    $calculator->calculateInvoice($invoice);
                }

                // 2. Payment Status Logic
                if ($invoice->getStatus() === 'PAID') {
                    // If marked PAID, ensure we have a payment date
                    if (!$invoice->getPaidAt()) {
                        $invoice->setPaidAt($now);
                    }
                } elseif ($invoice->getStatus() === 'SENT') {
                    // logic for reverting to SENT
                    // We clear the Paid Date.
                    $invoice->setPaidAt(null);
                }
            }

            // Recalculate the invoice totals if it's still a draft and any changes were made
            if ($invoice->getStatus() === 'DRAFT') {
                $calculator->calculateInvoice($invoice);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Invoice updated successfully!');
            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_invoice_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Invoice $invoice): Response
    {
        if ($invoice->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You do not have access to this invoice.');
        }

        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($invoice->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        // Only Drafts can be deleted
        if (!$invoice->isDeletable()) {
            $this->addFlash('error', 'Cannot delete a finalized invoice.');
            return $this->redirectToRoute('app_invoice_index');
        }

        if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
            $this->addFlash('success', 'Draft deleted successfully!');
        }

        return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    }
}
