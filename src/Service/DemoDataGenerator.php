<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

/**
 * Service responsible for populating a User account with realistic fake data.
 * Used primarily for the "Guest/Recruiter Access" feature.
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
            'E-commerce Platform Migration',
            'Corporate Website Redesign',
            'Custom CRM Development',
            'Mobile Application MVP',
            'Cloud Infrastructure Setup',
            'SEO Performance Audit',
            'UI/UX Prototyping (Figma)',
            'Backend API Development'
        ];

        // 1. Create Clients
        $clients = [];
        for ($j = 1; $j <= 10; $j++) {
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

        // BUCKET 1: DEEP HISTORY (-4 Years to -2 Months)

        foreach ($clients as $client) {
            for ($k = 1; $k <= rand(5, 8); $k++) {
                $date = $faker->dateTimeBetween('-4 years', '-2 months');
                $this->createSingleInvoice($user, $client, $date, $faker, $projectTitles, 'HISTORY');
            }
        }

        // BUCKET 2: RECENT PAST (-30 Days to -1 Day)

        for ($i = 0; $i < rand(4, 6); $i++) {
            $randomClient = $faker->randomElement($clients);
            $date = $faker->dateTimeBetween('-28 days', '-2 days');
            $this->createSingleInvoice($user, $randomClient, $date, $faker, $projectTitles, 'RECENT');
        }

        // BUCKET 3: CURRENT MONTH (1st to Today)

        $currentMonthStart = new \DateTimeImmutable('first day of this month');
        $today = new \DateTimeImmutable('now');
        $maxDays = (int)$today->format('d') - 1;

        for ($i = 0; $i < rand(3, 5); $i++) {
            $randomClient = $faker->randomElement($clients);
            $randomDays = $maxDays > 0 ? rand(0, $maxDays) : 0;
            $thisMonthDate = $currentMonthStart->modify("+$randomDays days");

            $this->createSingleInvoice($user, $randomClient, $thisMonthDate, $faker, $projectTitles, 'CURRENT');
        }

        // BUCKET 4: GUARANTEED DRAFTS

        for ($i = 0; $i < rand(3, max: 5); $i++) {
            $randomClient = $faker->randomElement($clients);
            $date = $faker->dateTimeBetween('-10 days', 'now');
            $this->createSingleInvoice($user, $randomClient, $date, $faker, $projectTitles, 'DRAFTS');
        }

        $this->entityManager->flush();
    }

    private function createSingleInvoice(User $user, Client $client, \DateTimeInterface $date, $faker, array $projectTitles, string $bucket): void
    {
        $invoice = new Invoice();
        $invoice->setClient($client);
        $invoice->setUser($user);
        $invoice->setProjectTitle($faker->randomElement($projectTitles));
        $invoice->setCurrency('EUR');

        $creationDate = \DateTimeImmutable::createFromInterface($date);

        // createdAt
        $reflection = new \ReflectionProperty(get_class($invoice), 'createdAt');
        $reflection->setValue($invoice, $creationDate);

        $uniqueSuffix = rand(10000, 99999);

        // --- STATUS LOGIC BY BUCKET ---

        if ($bucket === 'DRAFTS') {
            $invoice->setStatus('DRAFT');
        } elseif ($bucket === 'CURRENT') {
            $r = rand(1, 10);
            if ($r <= 2) $invoice->setStatus('DRAFT');
            elseif ($r <= 6) $invoice->setStatus('SENT');
            else {
                $invoice->setStatus('PAID');
                $invoice->setPaidAt($creationDate->modify('+1 day'));
            }
        } elseif ($bucket === 'RECENT') {
            $status = rand(1, 10) <= 8 ? 'SENT' : 'PAID';
            $invoice->setStatus($status);

            if ($status === 'PAID') {
                $invoice->setPaidAt($creationDate->modify('+1 day'));
            }
        } else { // HISTORY
            $status = rand(1, 100) <= 85 ? 'PAID' : 'SENT';
            $invoice->setStatus($status);
            if ($status === 'PAID') {
                $invoice->setPaidAt($creationDate->modify('+' . rand(5, 45) . ' days'));
            }
        }

        // handling the DRAFT number format
        if ($invoice->getStatus() !== 'DRAFT') {
            $invoice->setSentAt($creationDate);
            $invoice->setDueDate($creationDate->modify('+30 days'));
            $invoice->setInvoiceNumber("INV-" . $creationDate->format('Y') . "-" . $uniqueSuffix);
        } else {
            $invoice->setInvoiceNumber("DRAFT-" . $uniqueSuffix);
        }

        // --- VAT & ITEMS ---
        $vatRate = 0.0; // Mainting art 293b
        $totalHT = 0.0;

        for ($l = 1; $l <= rand(1, 4); $l++) {
            $qty = rand(1, 5);
            $unit = $faker->randomFloat(2, 200, 1500);
            $lineHT = $qty * $unit;

            $item = new InvoiceItem();
            $item->setDescription($faker->sentence(3));
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

        if (method_exists($invoice, 'collectSnapshot')) {
            $invoice->collectSnapshot();
        }

        $this->entityManager->persist($invoice);
    }
}
