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

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $unitPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $vatRate = '0.00';

    // --- Calculated Fields (Stored for history) ---

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $totalHt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $vatAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $totalTtc = null;

    // --- Relationship ---

    #[ORM\ManyToOne(inversedBy: 'invoiceItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Invoice $invoice = null;

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

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

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

    public function getVatRate(): ?string
    {
        return $this->vatRate;
    }

    public function setVatRate(?string $vatRate): static
    {
        // Normalize empty or null values
        if ($vatRate === null || $vatRate === '') {
            $vatRate = '0.00';
        }
        // formatting (2 decimals)
        $vatRate = number_format((float)$vatRate, 2, '.', '');
        $this->vatRate = $vatRate;
        return $this;
    }

    public function getTotalHt(): ?string
    {
        return $this->totalHt;
    }

    public function setTotalHt(?string $totalHt): static
    {
        $this->totalHt = $totalHt;

        return $this;
    }

    public function getVatAmount(): ?string
    {
        return $this->vatAmount;
    }

    public function setVatAmount(?string $vatAmount): static
    {
        $this->vatAmount = $vatAmount;

        return $this;
    }

    public function getTotalTtc(): ?string
    {
        return $this->totalTtc;
    }

    public function setTotalTtc(?string $totalTtc): static
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
}
