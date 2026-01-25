<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 2; $i++) {

            // 1. USER
            $user = new User();
            $user->setEmail("user$i@test.com");
            $user->setFirstName("Prenom$i");
            $user->setLastName("Nom$i");
            $user->setCompanyName("Freelance $i");
            $user->setSiretNumber("1234567890001$i");

            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);

            $manager->persist($user);

            for ($j = 1; $j <= 2; $j++) {

                // 2. CLIENT
                $client = new Client();
                $client->setFirstName("ClientPrenom$j");
                $client->setLastName("ClientNom$j");
                $client->setCompanyName("Client Company $i-$j");
                $client->setPhoneNumber("+3312345678$j");
                $client->setAddress("$j Rue Exemple");
                $client->setEmail("client$j.user$i@example.com");
                $client->setSiret("123456789000$j");
                $client->setVatNumber("FR12345678$j");
                $client->setCity("Paris");
                $client->setPostCode("7500$j");
                $client->setCountry("France");
                $client->setUser($user);

                $manager->persist($client);

                for ($k = 1; $k <= 5; $k++) {

                    // 3. INVOICE
                    $invoice = new Invoice();
                    $invoice->setClient($client);
                    $invoice->setUser($user);
                    $invoice->setProjectTitle("Project $k for Client $j");
                    
                    // We set it to SENT so we can test the "Locked" logic
                    $invoice->setStatus('SENT');
                    $invoice->setSentAt(new \DateTimeImmutable());
                    $invoice->setDueDate((new \DateTimeImmutable())->modify('+30 days'));

                    // Manually generate number for fixtures (using our new 'INV-' format)
                    $invoiceNumber = "INV-2026-" . str_pad((string)(($i - 1) * 10 + ($j - 1) * 5 + $k), 3, '0', STR_PAD_LEFT);
                    $invoice->setInvoiceNumber($invoiceNumber);

                    // Frozen client snapshot
                    $invoice->setFrozenClientName($client->getFirstName() . ' ' . $client->getLastName());
                    $invoice->setFrozenClientAddress($client->getAddress());
                    $invoice->setFrozenClientSiret($client->getSiret());
                    $invoice->setFrozenClientVat($client->getVatNumber());
                    $invoice->setFrozenClientCompanyName($client->getCompanyName());

                    $manager->persist($invoice);

                    // 4. INVOICE ITEMS
                    $totalHT = 0.0;
                    $totalVAT = 0.0;
                    $totalTTC = 0.0;

                    for ($l = 1; $l <= 3; $l++) {

                        $qty = rand(1, 5);
                        $unit = rand(50, 500); // Random price between 50 and 500
                        $vatRate = 20.0;

                        $lineHT = $qty * $unit;
                        $lineVAT = $lineHT * ($vatRate / 100);
                        $lineTTC = $lineHT + $lineVAT;

                        $item = new InvoiceItem();
                        $item->setDescription("Service line $l");
                        
                        // IMPORTANT: Cast to (string) because Entity expects DECIMAL strings
                        $item->setQuantity((string) $qty);
                        $item->setUnitPrice((string) $unit);
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

                    // 5. FINAL TOTALS (Cast to string)
                    $invoice->setTotalHt((string) number_format($totalHT, 2, '.', ''));
                    $invoice->setTotalVat((string) number_format($totalVAT, 2, '.', ''));
                    $invoice->setTotalAmount((string) number_format($totalTTC, 2, '.', ''));
                }
            }
        }

        $manager->flush();
    }
}