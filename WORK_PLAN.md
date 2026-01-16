# Project Roadmap — FreelanceFlow

## Part 1: The MVP (Minimum Viable Product)
*Goal: A functional app to manage, calculate, and export invoices.*

### Phase 1: Architecture & Authentication
- [ ] Symfony 7.4 Webapp initialization.
- [ ] Database configuration (MySQL).
- [ ] `User` entity & Login/Logout system.
- [ ] Registration form generation.
- [ ] Tailwind CSS integration via AssetMapper.

### Phase 2: Data Modeling & Business Logic
- [ ] **Database Design:** Create and document the database schema (MCD, MLD, ERD) via Mocodo.
- [ ] Entity Client: Create the entity + link to User (Freelancer).
- [ ] Entity Invoice: Create the entity with fields (number, date, status, due date).
- [ ] Entity InvoiceItem: Create the line items (description, quantity, unit price).
- [ ] Data Security (Voters): Ensure a Freelancer can only see their own clients and invoices.

### Phase 3: CRM - Client Management
- [ ] Client list interface with search/filters.
- [ ] Client creation and update forms.
- [ ] Secure client deletion.

### Phase 4: Security & Data Isolation (Voters)
- [ ] Implement **Symfony Voters** for Clients.
- [ ] Implement **Symfony Voters** for Invoices.
- [ ] *Guarantee: Multi-tenant isolation (Users only see their own data).*

### Phase 5: Invoicing Engine (UI)
- [ ] Invoice creation form.
- [ ] Dynamic line-item management (JavaScript/Stimulus).
- [ ] Issue and due date management.

### Phase 6: Business Logic & Taxes
- [ ] Automatic numbering system (e.g., `FF-2026-001`).
- [ ] `TaxCalculatorService` for net total calculations.
- [ ] Provisionary URSSAF tax estimates (21.2% rate).

### Phase 7: Invoice Design & Templates
- [ ] Professional HTML/CSS invoice template design.
- [ ] Integration of mandatory legal mentions (Art. 293B).
- [ ] Footer layout (SIRET, Contact info).

### Phase 8: PDF Generation
- [ ] DomPDF installation & configuration.
- [ ] HTML to PDF conversion.
- [ ] Secure PDF download functionality.

### Phase 9: Dashboard & Insights
- [ ] Revenue statistics (Monthly/Yearly).
- [ ] Visual tracking for Micro-entrepreneur revenue ceilings.
- [ ] Recent invoice activity list.

### Phase 10: MVP Optimization & Polish
- [ ] Flash message management (Success/Error).
- [ ] SQL query optimization (Eager loading).
- [ ] Final installation documentation.

---

## Part 2: Version 2 (Planned Evolutions)

### Phase 11: Email Automation
- [ ] Direct PDF delivery via Symfony Mailer.
- [ ] Customizable email templates.

### Phase 12: Sales Cycle (Quotes)
- [ ] Quote/Estimate management.
- [ ] One-click "Convert to Invoice" feature.

### Phase 13: Online Payments
- [ ] Stripe API integration for credit card payments.
- [ ] Automated "Paid" status updates via Webhooks.

### Phase 14: API & DevOps
- [ ] Data exposure via REST API (JWT).
- [ ] Docker containerization for production.