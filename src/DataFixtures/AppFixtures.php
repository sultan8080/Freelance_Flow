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
        // 1. Initialize Faker with French Locale
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 5; $i++) {

            // 2. USER (Freelancer)
            $user = new User();
            $user->setEmail("user$i@test.com"); 
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setCompanyName($faker->company());
            
            // Remove spaces for a valid-looking SIRET number string
            $siret = str_replace(' ', '', $faker->siret());
            $user->setSiretNumber($siret);

            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);

            $manager->persist($user);

            for ($j = 1; $j <= 5; $j++) { 

                // 3. CLIENT
                $client = new Client();
                $client->setFirstName($faker->firstName());
                $client->setLastName($faker->lastName());
                $client->setCompanyName($faker->company());
                $client->setPhoneNumber($faker->phoneNumber());
                $client->setAddress($faker->streetAddress());
                $client->setEmail($faker->email());
                
                $clientSiret = str_replace(' ', '', $faker->siret());
                $client->setSiret($clientSiret);
                $client->setVatNumber("FR" . substr($clientSiret, 0, 11)); // Fake VAT logic
                
                $client->setCity($faker->city());
                $client->setPostCode(str_replace(' ', '', $faker->postcode()));
                $client->setCountry("France");
                $client->setUser($user);

                $manager->persist($client);

                for ($k = 1; $k <= rand(3, 8); $k++) {

                    // 4. INVOICE
                    $invoice = new Invoice();
                    $invoice->setClient($client);
                    $invoice->setUser($user);
                    $invoice->setProjectTitle($faker->sentence(3)); 
                    
                    // Status Logic
                    $statuses = ['SENT', 'DRAFT', 'PAID'];
                    $status = $faker->randomElement($statuses);
                    $invoice->setStatus($status);

                    $now = new \DateTimeImmutable();
                    
                    // Unique suffix for number
                    $uniqueSuffix = str_pad((string)(($i * 1000) + ($j * 100) + $k), 4, '0', STR_PAD_LEFT);

                    if ($status === 'DRAFT') {
                        $invoice->setInvoiceNumber("DRAFT-" . $uniqueSuffix);
                        $invoice->setSentAt(null);
                        $invoice->setDueDate(null);
                        $invoice->setPaidAt(null);
                        
                        // Clear snapshot for drafts
                        $invoice->setFrozenClientName(null);
                        $invoice->setFrozenClientAddress(null);
                        $invoice->setFrozenClientSiret(null);
                        $invoice->setFrozenClientVat(null);
                        $invoice->setFrozenClientCompanyName(null);
                    } else {
                        $invoice->setInvoiceNumber("INV-2026-" . $uniqueSuffix);
                        
                        // Dates
                        $daysAgo = $faker->numberBetween(0, 60);
                        $sentDate = $now->modify("-$daysAgo days");
                        $invoice->setSentAt($sentDate);
                        $invoice->setDueDate($sentDate->modify('+30 days'));

                        // Snapshot
                        $invoice->setFrozenClientName($client->getFirstName() . ' ' . $client->getLastName());
                        $invoice->setFrozenClientAddress($client->getAddress());
                        $invoice->setFrozenClientSiret($client->getSiret());
                        $invoice->setFrozenClientVat($client->getVatNumber());
                        $invoice->setFrozenClientCompanyName($client->getCompanyName());

                        if ($status === 'PAID') {
                            $invoice->setPaidAt($now);
                        }
                    }

                    $manager->persist($invoice);

                    // 5. INVOICE ITEMS
                    $totalHT = 0.0;
                    $totalVAT = 0.0;
                    $totalTTC = 0.0;

                    for ($l = 1; $l <= rand(2, 5); $l++) { 
                        $qty = $faker->numberBetween(1, 10);
                        $unit = $faker->randomFloat(2, 50, 1000); 
                        $vatRate = 20.0;

                        $lineHT = $qty * $unit;
                        $lineVAT = $lineHT * ($vatRate / 100);
                        $lineTTC = $lineHT + $lineVAT;

                        $item = new InvoiceItem();
                        $item->setDescription($faker->sentence(4)); 
                        
                        $item->setQuantity((string) $qty);
                        $item->setUnitPrice((string) number_format($unit, 2, '.', ''));
                        $item->setVatRate((string) $vatRate);
                        
                        $item->setTotalHt((string) number_format($lineHT, 2, '.', ''));
                        $item->setVatAmount((string) number_format($lineVAT, 2, '.', ''));
                        $item->setTotalTtc((string) number_format($lineTTC, 2, '.', ''));
                        
                        $item->setInvoice($invoice);
                        $manager->persist($item);

                        $totalHT += $lineHT;
                        $totalVAT += $lineVAT;
                        $totalTTC += $lineTTC;
                    }

                    $invoice->setTotalHt((string) number_format($totalHT, 2, '.', ''));
                    $invoice->setTotalVat((string) number_format($totalVAT, 2, '.', ''));
                    $invoice->setTotalAmount((string) number_format($totalTTC, 2, '.', ''));
                }
            }
        }

        $manager->flush();
    }
}