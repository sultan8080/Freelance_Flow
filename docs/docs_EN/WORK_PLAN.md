# Project Roadmap — Freelance Flow

## Part 1: The MVP (Minimum Viable Product)
*Goal: A functional app to manage, calculate, and export invoices.*

### Phase 1: Architecture & Authentication
- [x] Symfony 7.4 Webapp initialization.
- [x] Database configuration (MySQL).
- [x] `User` entity & Login/Logout system.
- [x] Registration form generation.
- [x] Tailwind CSS integration via AssetMapper.

### Phase 2: Data Modeling & Business Logic
- [x] **Database Design:** Create and document the database schema (MCD, MLD, ERD) via Mocodo.net.
- [x] Entity Client: Create the entity + link to User (Freelancer).
- [x] Entity Invoice: Create the entity with fields (number, date, status, due date).
- [x] Entity InvoiceItem: Create the line items (description, quantity, unit price).
- [x] Data Security (Voters): Ensure a Freelancer can only see their own clients and invoices.

### Phase 3: The Complete CRM & Profile Build
- [x] Role Setup: Define ROLE_ADMIN vs ROLE_USER in security.yaml.
- [x] Profile Form (UserType): Create a form for professional data (SIRET, VAT, Company Name, Address).
- [x] Account Settings: Build SettingsController to allow freelancers to complete their professional identity.
- [x] Client Form (ClientType): Create the form to add and edit customers.
- [x] Client CRUD: Generate the interface to list, view, and delete clients.
- [x] Basic Filtering: Ensure the list only shows clients linked to a user (pre-Voter stage).

### Phase 4: Global UI & Security 
- [ ] **Global Sidebar Layout:** Refactor `base.html.twig` with a professional navigation (Dashboard, Clients, Invoices, Settings).
- [ ] **Freelancer Dashboard:** Create `DashboardController` for the home landing page.
- [ ] **Security Voters:** Implement `ClientVoter` and `InvoiceVoter` for multi-tenant data isolation.
- [ ] **Access Control:** Finalize `security.yaml` to protect all authenticated routes.

### Phase 5: Invoicing Engine (Backend & Logic)
- [ ] **Entity Audit:** Verify relationships between `User`, `Client`, `Invoice`, and `InvoiceItem`.
- [ ] **Numbering Service:** Sequential invoice ID generator (e.g., `FF-2026-001`).
- [ ] **Financial Service:** Logic for Subtotal, VAT, and Total TTC calculations.
- [ ] **URSSAF Estimator:** Service to estimate social taxes (21.2%).

### Phase 6: Dynamic Invoicing (Frontend UI)
- [ ] **Invoice Form:** Implementation using Symfony `CollectionType`.
- [ ] **Stimulus.js:** Dynamic "Add/Remove Line Item" functionality without page reload.
- [ ] **Live Totals:** Real-time JavaScript calculation of totals on the form.

### Phase 7: Document Design & Export
- [ ] **HTML Template:** Professional layout including "Art. 293B" legal mentions.
- [ ] **PDF Engine:** Integration of DomPDF for document generation.
- [ ] **Secure Export:** Protected routes for PDF downloads.

### Phase 8: Dashboard Insights (Analytics)
- [ ] **KPI Widgets:** Monthly/Yearly revenue tracking.
- [ ] **Ceiling Tracker:** Progress bar for auto-entrepreneur revenue limits.
- [ ] **Activity Feed:** Quick view of pending/overdue invoices.

### Phase 9: UX Optimization & Polish
- [ ] **Flash Message System:** Professional toast notifications for CRUD actions (Success/Error).
- [ ] **Empty State Designs:** Create high-quality placeholder views for users with 0 clients or invoices.
- [ ] **Navigation Refinement:** Add "Active" states to sidebar links to show the user where they are.
- [ ] **Form Validation:** Enhance client-side and server-side error messaging for better user guidance.

### Phase 10: Performance & Deployment Readiness
- [ ] **SQL Query Tuning:** Implement Eager Loading (`JOIN`) in Repositories to eliminate N+1 query issues.
- [ ] **Legal Verification:** Final check of French mandatory mentions (SIRET, VAT logic, Art. 293B).
- [ ] **Production Config:** Setup environment variables (`.env.local`), security headers, and asset compression.
- [ ] **Project Documentation:** Complete the `README.md` with installation and contribution guides.

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