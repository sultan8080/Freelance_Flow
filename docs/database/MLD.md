
##  Modèle Logique de Données (MLD) — FreelanceFlow

### USER
> Représente le freelance propriétaire du compte.
> User (idUser PK, email, password, firstName, lastName, companyName, address siretNumber, vatNumber,phoneNumber, createdAt,updatedAt)

### CLIENT
> Un client appartient à un seul utilisateur (freelancer).
> Client (idClient PK, lastName, firstName, companyName, email, address, phoneNumber, createdAt, updatedAt, idUser FK → User(idUser) )

### INVOICE
> Une facture est liée à un client.
> Invoice (idInvoice PK, invoiceNumber, dateCreated, dueDate, status, totalAmount, currency, paidAt, updatedAt, idClient FK → Client(idClient) )

### INVOICE_ITEM
> Lignes de détails d’une facture.
> InvoiceItem (idInvoiceItem PK, description, unitPrice, quantity, idInvoice FK → Invoice(idInvoice))


> **Important Note**  
> This logical data model was manually refined from an auto-generated Mocodo schema
> to ensure **Doctrine ORM compatibility**, relational best practices, and **SaaS multi-tenant data isolation**.


### Sécurité et isolation des données

- Chaque **Client**, **Invoice** et **InvoiceItem** est indirectement lié à un **User**.  
- L’accès aux données est contrôlé via **Symfony Voters**.  
- Un utilisateur ne peut voir/modifier que les ressources qu’il possède.  
- Cette approche garantit une **architecture multi-tenant SaaS**, même avec une seule base de données.





