<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Environment;


/**
 * Service responsible for generating PDF documents from Twig templates.
 * Wraps the Dompdf library to provide a streamlined interface for invoice and report generation.
 */

class PdfGenerator
{
    public function __construct(
        private Environment $twig,
        #[Autowire('%kernel.project_dir%')] private string $projectDir
    ) {}

    public function generatePdf(string $template, array $data = []): string
    {
        $options = new Options();

        // Enable loading of remote images (e.g., from the public folder or CDN)
        $options->set('isRemoteEnabled', true);

        // Fix font issues
        $options->set('defaultFont', 'Arial');
        
        // Allow access to local files for AssetMapper
        $options->set('chroot', $this->projectDir . '/public');

        $dompdf = new Dompdf($options);

        // Render the Twig template to HTML
        $html = $this->twig->render($template, $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}