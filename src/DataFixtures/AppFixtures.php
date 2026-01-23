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
            $user = new User();
            $user->setEmail("user$i@test.com");
            $user->setFirstName("prenom$i");
            $user->setLastName("nom$i");
            $user->setCompanyName("Freelance $i");
            $user->setSiretNumber("1234567890001$i");

            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);

            $manager->persist($user);

            for ($j = 1; $j <= 2; $j++) {
                $client = new Client();
                $client->setFirstName("prenom$j");
                $client->setLastName("nom$j");
                $client->setCompanyName("Client Company $i-$j");
                $client->setPhoneNumber("+3312345678$j");
                $client->setAddress("$j Rue de Exemple, 7500$j Paris, France");
                $client->setEmail("client$j.from.user$i@example.com");
                $client->setUser($user);
                
                $manager->persist($client);

                for ($k = 1; $k <= 5; $k++) {
                    $invoice = new Invoice();
                    // Link to BOTH client and user
                    $invoice->setClient($client);
                    $invoice->setUser($user); // <--- NEW DIRECT LINK
                    
                    $invoice->setProjectTitle("Project $k for Client $j"); // <--- NEW FIELD
                    $invoice->setInvoiceNumber("FAC-2026-" . str_pad((string)(($i-1)*10 + ($j-1)*5 + $k), 3, '0', STR_PAD_LEFT));
                    $invoice->setTotalAmount(strval(rand(100, 5000)));
                    $invoice->setCurrency('EUR');
                    $invoice->setStatus('sent');
                    
                    $manager->persist($invoice);

                    for ($l = 1; $l <= 5; $l++) {
                        $item = new InvoiceItem();
                        $item->setDescription("Service delivery line $l");
                        $item->setQuantity((float)rand(1, 10));
                        $item->setUnitPrice(strval(rand(50, 500)));
                        $item->setInvoice($invoice);
                        
                        $manager->persist($item);
                    }
                }
            }
        }

        $manager->flush();
    }
}