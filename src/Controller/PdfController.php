<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Service\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class PdfController extends AbstractController
{
    #[Route('/invoice/{id}/export', name: 'app_invoice_export_pdf', methods: ['GET'])]
    public function downloadPdf(Invoice $invoice, PdfGenerator $pdfGenerator): Response
    {
        // Ensure the user has access to the invoice
        if ($invoice->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot view this invoice.');
        }

        // get the invoice no and sanitize it for filename use
        $invoiceNo = $invoice->getInvoiceNumber();
        $safeInvoiceNo = $invoiceNo ? preg_replace('/[^a-zA-Z0-9_\-]/', '_', $invoiceNo) : 'invoice';

        // Define the filename with invoice id to ensure uniqueness
        $filename = sprintf('Invoice_%s_%s.pdf', $safeInvoiceNo, $invoice->getId());
        // Load CSS content
        $cssPath = $this->getParameter('kernel.project_dir') . '/public/assets/styles/app.css';
        $cssContent = file_exists($cssPath) ? file_get_contents($cssPath) : '';

        // Generate PDF
        $pdfContent = $pdfGenerator->generatePdf('pdf/invoice.html.twig', [
            'invoice' => $invoice,
            'user' => $this->getUser(),
            'css_content' => $cssContent,
            'date' => new \DateTime(),
        ]);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_INLINE,
            $filename
        );

        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => $disposition,
            ]
        );
    }
}
