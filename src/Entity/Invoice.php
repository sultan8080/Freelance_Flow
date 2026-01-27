<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Invoice
{
    use \App\Entity\Traits\TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $invoiceNumber = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dueDate = null;

    #[ORM\Column(length: 20, options: ['default' => 'DRAFT'])]
    private ?string $status = 'DRAFT';

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $sentAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $paidAt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['default' => '0.00'])]
    private ?string $totalHt = '0.00';

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['default' => '0.00'])]
    private ?string $totalVat = '0.00';

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['default' => '0.00'])]
    private ?string $totalAmount = '0.00';

    #[ORM\Column(length: 255, options: ['default' => 'EUR'])]
    private ?string $currency = 'EUR';

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToMany(targetEntity: InvoiceItem::class, mappedBy: 'invoice', orphanRemoval: true, cascade: ['persist'])]
    private Collection $invoiceItems;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $projectTitle = null;

    // --- SNAPSHOT FIELDS (Frozen Data) ---

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frozenClientName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frozenClientAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frozenClientSiret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frozenClientVat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frozenClientCompanyName = null;

    public function __construct()
    {
        $this->invoiceItems = new ArrayCollection();
        $this->status = 'DRAFT';
        $this->currency = 'EUR';
        $this->totalHt = '0.00';
        $this->totalVat = '0.00';
        $this->totalAmount = '0.00';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): static
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getDueDate(): ?\DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeImmutable $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        // 1. Transition to SENT (Lock the client information)
        if ($status === 'SENT') {
            if ($this->sentAt === null) {
                $this->sentAt = new \DateTimeImmutable();
            }

            if ($this->dueDate === null) {
                $this->dueDate = (new \DateTimeImmutable())->modify('+30 days');
            }
            // Freeze client data immediately
            $this->collectSnapshot();
        }

        // 2. Transition to PAID
        if ($status === 'PAID' && $this->paidAt === null) {
            $this->paidAt = new \DateTimeImmutable();
        }
        return $this;
    }


    // Check if the document is in "Mute Mode"
    public function isLocked(): bool
    {
        return in_array($this->status, ['SENT', 'PAID', 'UNPAID']);
    }

    // check if the user can delete this from the database?
    public function isDeletable(): bool
    {
        return $this->status === 'DRAFT';
    }

    /**
     * Checks if the invoice is Sent but the Due Date has passed.
     */
    public function isOverdue(): bool
    {
        // 1. If it's Draft or Paid, it can't be overdue
        if ($this->status === 'DRAFT' || $this->status === 'PAID') {
            return false;
        }

        // 2. If no Due Date is set, it can't be overdue
        if ($this->dueDate === null) {
            return false;
        }

        // 3. Check if Today > Due Date
        $today = new \DateTimeImmutable('today'); 

        // Return true if Today is strictly after the Due Date
        return $today > $this->dueDate;
    }

    // Capture snapshot of client data
    public function collectSnapshot(): void
    {
        if ($this->client) {
            $this->frozenClientName = trim($this->client->getFirstName() . ' ' . $this->client->getLastName());
            $this->frozenClientCompanyName = $this->client->getCompanyName();

            $this->frozenClientAddress = sprintf(
                "%s\n%s %s\n%s\nTel: %s",
                $this->client->getAddress(),
                $this->client->getPostCode(),
                $this->client->getCity(),
                $this->client->getCountry(),
                $this->client->getPhoneNumber()
            );

            $this->frozenClientSiret = $this->client->getSiret();
            $this->frozenClientVat = $this->client->getVatNumber();
        }
    }

    // --- Standard Getters and Setters below ---

    public function getTotalAmount(): ?string
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(string $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeImmutable $paidAt): static
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceItem>
     */
    public function getInvoiceItems(): Collection
    {
        return $this->invoiceItems;
    }

    public function addInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if (!$this->invoiceItems->contains($invoiceItem)) {
            $this->invoiceItems->add($invoiceItem);
            $invoiceItem->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if ($this->invoiceItems->removeElement($invoiceItem)) {
            if ($invoiceItem->getInvoice() === $this) {
                $invoiceItem->setInvoice(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getProjectTitle(): ?string
    {
        return $this->projectTitle;
    }

    public function setProjectTitle(?string $projectTitle): static
    {
        $this->projectTitle = $projectTitle;

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

    public function getTotalVat(): ?string
    {
        return $this->totalVat;
    }

    public function setTotalVat(string $totalVat): static
    {
        $this->totalVat = $totalVat;

        return $this;
    }

    public function getFrozenClientName(): ?string
    {
        return $this->frozenClientName;
    }

    public function setFrozenClientName(?string $frozenClientName): static
    {
        $this->frozenClientName = $frozenClientName;

        return $this;
    }

    public function getFrozenClientAddress(): ?string
    {
        return $this->frozenClientAddress;
    }

    public function setFrozenClientAddress(?string $frozenClientAddress): static
    {
        $this->frozenClientAddress = $frozenClientAddress;

        return $this;
    }

    public function getFrozenClientSiret(): ?string
    {
        return $this->frozenClientSiret;
    }

    public function setFrozenClientSiret(?string $frozenClientSiret): static
    {
        $this->frozenClientSiret = $frozenClientSiret;

        return $this;
    }

    public function getFrozenClientVat(): ?string
    {
        return $this->frozenClientVat;
    }

    public function setFrozenClientVat(?string $frozenClientVat): static
    {
        $this->frozenClientVat = $frozenClientVat;

        return $this;
    }

    public function getFrozenClientCompanyName(): ?string
    {
        return $this->frozenClientCompanyName;
    }

    public function setFrozenClientCompanyName(?string $frozenClientCompanyName): static
    {
        $this->frozenClientCompanyName = $frozenClientCompanyName;

        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTimeImmutable $sentAt): static
    {
        $this->sentAt = $sentAt;

        return $this;
    }
}
