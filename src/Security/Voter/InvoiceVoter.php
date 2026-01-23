<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Invoice;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class InvoiceVoter extends Voter
{

    public const VIEW = 'INVOICE_VIEW';
    public const EDIT = 'INVOICE_EDIT';
    public const DELETE = 'INVOICE_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW,  self::DELETE], true)
            && $subject instanceof \App\Entity\Invoice;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Invoice $invoice */
        $invoice = $subject;

        // Multi‑tenant isolation
        // 1. Ownership check (The "Gatekeeper")
        if ($invoice->getUser() !== $user) {
            return false;
        }

        // 2. Permission check (The "Rulebook"), for example based on status of the invoice (draft, sent, paid, etc.) if it's draft, allow edit and delete, otherwise only view
        return match ($attribute) {
            self::VIEW => true,
            self::EDIT, self::DELETE => $invoice->getStatus() === 'draft',
            default => false,
        };
    }
}
