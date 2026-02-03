<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $invoiceBucket = []; 

        $projectTitles = [
            'E-commerce Platform Migration', 'Corporate Website Redesign',
            'Custom CRM Development', 'Mobile Application MVP',
            'Cloud Infrastructure Setup', 'Quarterly Maintenance Contract',
            'Backend API Development', 'Frontend Component Library',
            'Database Optimization & Indexing', 'Unit & Integration Testing',
            'Third-party Payment Integration', 'Technical Documentation & Handover',
            'UI/UX Prototyping (Figma)', 'SEO Performance Audit'
        ];

        // 1. Create Freelance Users
        for ($i = 1; $i <= 3; $i++) {
            $user = new User();
            $user->setEmail("user$i@freelanceflow.com");
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setCompanyName($faker->company());
            $user->setAddress($faker->streetAddress());
            $user->setPostCode($faker->postcode());
            $user->setCity($faker->city());
            $user->setCountry("France");
            $user->setSiretNumber(str_replace(' ', '', $faker->siret()));
            $user->setPassword($this->hasher->hashPassword($user, 'password'));

            $manager->persist($user);

            // 2. Create Clients for each User
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

                $manager->persist($client);

                // 3. Generate Invoices 
                for ($k = 1; $k <= rand(30, 50); $k++) {
                    $invoice = new Invoice();
                    $invoice->setClient($client);
                    $invoice->setUser($user);
                    $invoice->setProjectTitle($faker->randomElement($projectTitles));
                    $invoice->setCurrency('EUR');

                    // --- TIME LOGIC ---
                    $randomDate = $faker->dateTimeBetween('-4 years', 'now');
                    $creationDate = \DateTimeImmutable::createFromMutable($randomDate);

                    // Force createdAt using Reflection
                    $reflection = new \ReflectionProperty(get_class($invoice), 'createdAt');
                    $reflection->setValue($invoice, $creationDate);

                    // --- STATUS LOGIC ---
                    $randomVal = rand(1, 10);
                    $uniqueSuffix = str_pad((string)(($i * 1000) + ($j * 100) + $k), 5, '0', STR_PAD_LEFT);

                    if ($randomVal === 1) { 
                        $invoice->setStatus('DRAFT');
                        $invoice->setInvoiceNumber("DRAFT-" . $uniqueSuffix);
                    } elseif ($randomVal <= 4) {
                        // Created & Sent same day
                        $status = rand(1, 2) === 1 ? 'SENT' : 'PAID';
                        $invoice->setStatus($status);
                        $invoice->setSentAt($creationDate); 
                        $invoice->setDueDate($creationDate->modify('+30 days'));
                        $invoice->setInvoiceNumber("INV-" . $creationDate->format('Y') . "-" . $uniqueSuffix);
                        
                        if ($status === 'PAID') {
                            $invoice->setPaidAt($creationDate);
                        }
                    } else {
                        // Realistic Delay
                        $status = rand(1, 2) === 1 ? 'SENT' : 'PAID';
                        $invoice->setStatus($status);
                        $sentAt = $creationDate->modify('+' . rand(1, 20) . ' days');
                        $invoice->setSentAt($sentAt);
                        $invoice->setDueDate($sentAt->modify('+30 days'));
                        $invoice->setInvoiceNumber("INV-" . $sentAt->format('Y') . "-" . $uniqueSuffix);
                        
                        if ($status === 'PAID') {
                            $invoice->setPaidAt($sentAt->modify('+' . rand(2, 45) . ' days'));
                        }
                    }

                    // 4. Create Invoice Items
                    $totalHT = 0.0;
                    for ($l = 1; $l <= rand(1, 5); $l++) {
                        $qty = rand(1, 5);
                        $unit = $faker->randomFloat(2, 100, 2000);
                        $lineHT = $qty * $unit;

                        $item = new InvoiceItem();
                        $item->setDescription($faker->sentence(rand(4, 8)));
                        $item->setQuantity((string)$qty);
                        $item->setUnitPrice((string)$unit);
                        $item->setVatRate("20.0");
                        $item->setTotalHt((string)$lineHT);
                        $item->setVatAmount((string)($lineHT * 0.20));
                        $item->setTotalTtc((string)($lineHT * 1.20));
                        $invoice->addInvoiceItem($item); 
                        $totalHT += $lineHT;
                    }

                    $invoice->setTotalHt((string)$totalHT);
                    $invoice->setTotalVat((string)($totalHT * 0.20));
                    $invoice->setTotalAmount((string)($totalHT * 1.20));

                    $invoice->collectSnapshot();

                    // Put in bucket for shuffling
                    $invoiceBucket[] = $invoice;
                }
            }
        }

        // 5. Shuffle the Bucket
        shuffle($invoiceBucket);

        // 6. Final Persist Loop
        $batchSize = 50;
        foreach ($invoiceBucket as $index => $invoice) {
            $manager->persist($invoice);

            if (($index % $batchSize) === 0) {
                $manager->flush();
            }
        }

        $manager->flush();
    }
}