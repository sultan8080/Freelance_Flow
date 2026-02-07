<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

/**
 * Service responsible for populating a User account with Realistic Growth Data.
 * Features:
 * - Continuous history since Jan 2023 (No gaps).
 * - Increasing volume over years (Growth trend).
 * - Mix of statuses (Paid, Sent/Overdue) to allow filtering.
 * - Active current month for dashboard charts.
 */
class DemoDataGenerator
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function generateFor(User $user): void
    {
        $faker = Factory::create('fr_FR');

        $projectTitles = [
            'E-commerce Platform Migration', 'Corporate Website Redesign', 
            'Custom CRM Development', 'Mobile Application MVP', 
            'Cloud Infrastructure Setup', 'SEO Performance Audit', 
            'UI/UX Prototyping (Figma)', 'Backend API Development',
            'Maintenance Retainer', 'Consulting Workshop'
        ];

        // 1. Create 10 Clients
        $clients = [];
        for ($j = 1; $j <= 25; $j++) {
            $client = new Client();
            $client->setFirstName($faker->firstName());
            $client->setLastName($faker->lastName());
            $client->setCompanyName($faker->company());
            $client->setPhoneNumber($faker->phoneNumber());
            $client->setAddress($faker->streetAddress());
            $client->setEmail($faker->email());
            $client->setSiret(str_replace(' ', '', $faker->siret()));
            $client->setCity($faker->city());
            $client->setPostCode(str_replace(' ', '', $faker->postcode()));
            $client->setCountry("France");
            $client->setUser($user);

            $this->entityManager->persist($client);
            $clients[] = $client;
        }

        // 2. TIMELINE GENERATION (Jan 2023 -> Today)
        
        $startDate = new \DateTimeImmutable('2023-01-01');
        $today = new \DateTimeImmutable('now');
        $currentIterator = clone $startDate;

        while ($currentIterator <= $today) {
            $year = (int)$currentIterator->format('Y');
            $month = (int)$currentIterator->format('m');
            $isCurrentMonth = ($currentIterator->format('Y-m') === $today->format('Y-m'));

            // --- A. GROWTH STRATEGY ---

            if ($year === 2023) {
                $min = 4; $max = 6;
            } elseif ($year === 2024) {
                $min = 7; $max = 10;
            } else {
                $min = 11; $max = 15;
            }

            // Boost volume if it's the current month 
            if ($isCurrentMonth) {
                $min = 8; $max = 12; 
            }

            $count = rand($min, $max);

            for ($k = 0; $k < $count; $k++) {
                $day = rand(1, 28); 
                if ($isCurrentMonth) {
                    $day = rand(1, max(1, (int)$today->format('d')));
                }

                $invoiceDate = $currentIterator->setDate($year, $month, $day);
                
                // Don't generate future dates
                if ($invoiceDate > $today) continue;

                // --- B. STATUS STRATEGY ---
                $daysOld = $today->diff($invoiceDate)->days;
                $status = 'PAID'; // Default

                if ($daysOld > 60) {
                    if (rand(1, 100) <= 10) {
                        $status = 'SENT'; 
                    }
                } elseif ($daysOld > 10) {

                    $status = rand(1, 100) <= 60 ? 'PAID' : 'SENT';
                } else {
                    $r = rand(1, 100);
                    if ($r <= 20) $status = 'DRAFT';
                    elseif ($r <= 80) $status = 'SENT';
                    else $status = 'PAID'; // Super fast payer
                }

                if ($daysOld < 5 && rand(1, 10) <= 3) {
                    $status = 'DRAFT';
                }

                $this->createSingleInvoice($user, $faker->randomElement($clients), $invoiceDate, $faker, $projectTitles, $status);
            }

            $currentIterator = $currentIterator->modify('first day of next month');
        }

        $this->entityManager->flush();
    }

    private function createSingleInvoice(User $user, Client $client, \DateTimeInterface $date, $faker, array $projectTitles, string $status): void
    {
        $invoice = new Invoice();
        $invoice->setClient($client);
        $invoice->setUser($user);
        $invoice->setProjectTitle($faker->randomElement($projectTitles));
        $invoice->setCurrency('EUR');

        $creationDate = \DateTimeImmutable::createFromInterface($date);

        // Reflection to force createdAt 
        $reflection = new \ReflectionProperty(get_class($invoice), 'createdAt');
        $reflection->setValue($invoice, $creationDate);

        // --- STATUS & DATES ---
        $invoice->setStatus($status);
        $uniqueSuffix = rand(10000, 99999);

        if ($status === 'DRAFT') {
            $invoice->setInvoiceNumber("DRAFT-" . $uniqueSuffix);
        } else {
            // SENT or PAID
            $invoice->setInvoiceNumber("INV-" . $creationDate->format('Y') . "-" . $uniqueSuffix);
            $invoice->setSentAt($creationDate);
            $invoice->setDueDate($creationDate->modify('+30 days'));

            if ($status === 'PAID') {
                // Pay it 2-15 days after creation
                $invoice->setPaidAt($creationDate->modify('+' . rand(2, 15) . ' days'));
            }
        }

        // --- ITEMS & AMOUNTS ---
    
        $year = (int)$creationDate->format('Y');
        if ($year === 2023) {
            $minPrice = 200; $maxPrice = 800;
        } else {
            $minPrice = 500; $maxPrice = 2000;
        }

        $vatRate = 0.0; // Auto-entrepreneur often 0%
        $totalHT = 0.0;

        for ($l = 1; $l <= rand(1, 3); $l++) {
            $qty = rand(1, 5);
            $unit = $faker->randomFloat(2, $minPrice, $maxPrice); 
            $lineHT = $qty * $unit;

            $item = new InvoiceItem();
            $item->setDescription($faker->jobTitle); 
            $item->setQuantity((string)$qty);
            $item->setUnitPrice((string)$unit);
            $item->setVatRate((string)$vatRate);
            $item->setTotalHt((string)$lineHT);
            $item->setVatAmount((string)($lineHT * ($vatRate / 100)));
            $item->setTotalTtc((string)($lineHT * (1 + ($vatRate / 100))));

            $invoice->addInvoiceItem($item);
            $totalHT += $lineHT;
        }

        $invoice->setTotalHt((string)$totalHT);
        $invoice->setTotalVat((string)($totalHT * ($vatRate / 100)));
        $invoice->setTotalAmount((string)($totalHT * (1 + ($vatRate / 100))));

        // Snapshot feature
        if (method_exists($invoice, 'collectSnapshot')) {
            $invoice->collectSnapshot();
        }

        $this->entityManager->persist($invoice);
    }
}