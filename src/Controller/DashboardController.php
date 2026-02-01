<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class DashboardController extends AbstractController
{
    #[Route('/dashboard_freelancer', name: 'dashboard_freelancer')]
    
    #[IsGranted('ROLE_USER')]
    
    public function index(
        InvoiceRepository $invoiceRepository,
        ClientRepository $clientRepository,
       ): Response {
        /** @var User $user */

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // 1. Invoices & Revenue 
        $monthlyRevenue = $invoiceRepository->getCurrentMonthRevenue($user);
        $draftCount = $invoiceRepository->countByStatus($user, 'DRAFT');
        $sentCount = $invoiceRepository->countByStatus($user, 'SENT');
        
        // 2. Client Metrics 
        $totalClients = $clientRepository->countTotalClients($user);
        $newClientsThisMonth = $clientRepository->countNewClientsThisMonth($user);

        // 3. Recent Activity
        $recentInvoices = $invoiceRepository->findRecentInvoices($user, 5);

        return $this->render('dashboard/index.html.twig', [
            'monthly_revenue' => $monthlyRevenue,
            'counts' => [
                'draft' => $draftCount,
                'sent' => $sentCount,
            ],
            'clients' => [
                'total' => $totalClients,
                'new_this_month' => $newClientsThisMonth
            ],
            'recent_invoices' => $recentInvoices,
            'now' => new \DateTime(), // Current month display
        ]);
    }
    // public function index(): Response
    // {
       
    //     return $this->render('dashboard/index.html.twig', [
    //         'controller_name' => 'DashboardController',
    //     ]);
    // }
}
