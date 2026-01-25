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
            'invoices' => $invoiceRepository->findBy(['user' => $this->getUser()], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new_invoice', name: 'app_invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, InvoiceCalculator $calculator, InvoiceNumberGenerator $generator): Response
    {
        $invoice = new Invoice();
        $invoice->setUser($this->getUser());

        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 1. Calculate the Money (HT, VAT, TTC)
            $calculator->calculateInvoice($invoice);

            // 2. Handle Status Logic (Draft -> Sent)
            if ($invoice->getStatus() === 'SENT') {

                // Assign an valide invoice number if it doesn't have one
                if (!$invoice->getInvoiceNumber()) {
                    $invoice->setInvoiceNumber($generator->generateFor($this->getUser()));
                }
                // Capture snapshot of client data at sending time
                $invoice->collectSnapshot();
            }

            $entityManager->persist($invoice);
            $entityManager->flush();

            $this->addFlash('success', 'Invoice saved successfully!');
            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please correct the errors in the form.');
            return $this->render('invoice/new.html.twig', [
                'form' => $form,
                'invoice' => $invoice, // Pass invoice for template logic
            ], new Response(null, 422));
        }

        return $this->render('invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        // Security check (Vote)
        if ($invoice->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You do not have access to this invoice.');
        }

        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
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
        // Security check
        if ($invoice->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You do not have access to this invoice.');
        }

        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 1. Recalculate Money 
            $calculator->calculateInvoice($invoice);

            // 2. Check Transitions (Draft -> Sent)
            if (in_array($invoice->getStatus(), ['SENT', 'PAID', 'UNPAID'])) {

                if (!$invoice->getInvoiceNumber()) {
                    $invoice->setInvoiceNumber($generator->generateFor($this->getUser()));

                    // Force snapshot capture on first send
                    $invoice->collectSnapshot();
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Invoice updated successfully!');

            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please correct the errors in the form.');
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
        if ($invoice->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        // Only DRAFT invoices can be deleted
        if (!$invoice->isDeletable()) {
            $this->addFlash('error', 'Cannot delete a Sent invoice.');
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
