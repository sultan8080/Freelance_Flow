<?php

namespace App\Entity;

use App\Repository\InvoiceItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceItemRepository::class)]
class InvoiceItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['default' => '0.00'])]
    private ?string $unitPrice = '0.00';

    #[ORM\Column]
    private ?float $quantity = 1.0;

    #[ORM\ManyToOne(inversedBy: 'invoiceItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Invoice $invoice = null;

    #[ORM\Column(type: 'float', options: ['default' => 0.0])]
    private ?float $vatRate = 0.0;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['default' => '0.00'])]
    private ?string $vatAmount = '0.00';
    
    #[ORM\Column(type: 'decimal', precision: 12, scale: 2, options: ['default' => '0.00'])]
    private ?string $totalHt = '0.00';

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['default' => '0.00'])]
    private ?string $totalTtc = '0.00';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(string $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getVatRate(): ?float
    {
        return $this->vatRate;
    }
    public function setVatRate(float $vatRate): static
    {
        $this->vatRate = $vatRate;

        return $this;
    }
    public function getVatAmount(): ?string
    {
        return $this->vatAmount;
    }
    public function setVatAmount(string $vatAmount): static
    {
        $this->vatAmount = $vatAmount;

        return $this;
    }
    public function getTotalHt(): ?string
    {
        return $this->totalHt;
    }
    public function setTotalHt(string $totalHt): static
    {
        $this->totalHt = $totalHt;
        return $this;
    }

    public function getTotalTtc(): ?string
    {
        return $this->totalTtc;
    }
    public function setTotalTtc(string $totalTtc): static
    {
        $this->totalTtc = $totalTtc;
        return $this;
    }


    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }
    public function __construct()
{
    // Initialize numeric strings to prevent math errors
    $this->unitPrice = '0.00';
    $this->totalHt = '0.00';
    $this->vatAmount = '0.00';
    $this->totalTtc = '0.00';
    
    // Initialize floats/ints
    $this->quantity = 1.0;
    $this->vatRate = 20.0; // Or your most common default VAT rate
}
}
