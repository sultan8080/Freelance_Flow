# Project Roadmap — Freelance Flow

## PARTT 1 : VERSION 1 - The MVP (Minimum Viable Product)

_Goal: A functional app to manage, calculate, and export invoices._

### Phase 1: Architecture & Authentication

- [x] Symfony 7.4 Webapp initialization.
- [x] Database configuration (MySQL).
- [x] `User` entity & Login/Logout system.
- [x] Registration form generation.
- [x] Tailwind CSS integration via AssetMapper.

### Phase 2: Data Modeling & Business Logic

- [x] **Database Design:** Create and document the database schema (MCD, MLD, ERD) via Mocodo.net.
- [x] **Entity Client:** Create the entity + link to User (Freelancer).
- [x] **Entity Invoice:** Create the entity with fields (number, date, status, due date).
- [x] **Entity InvoiceItem:** Create the line items (description, quantity, unit price).
- [x] **Data Security (Voters):** Ensure a Freelancer can only see their own clients and invoices.

### Phase 3: The Complete CRM & Profile Build

- [x] **Role Setup:** Define ROLE_ADMIN vs ROLE_USER in security.yaml.
- [x] **Profile Form (UserType):** Create a form for professional data (SIRET, VAT, Company Name, Address).
- [x] **Account Settings:** Build SettingsController to allow freelancers to complete their professional identity.
- [x] **Client Form (ClientType):** Create the form to add and edit customers.
- [x] **Client CRUD:** Generate the interface to list, view, and delete clients.
- [x] **Basic Filtering:** Ensure the list only shows clients linked to a user (pre-Voter stage).

### Phase 4: Global Interface, Security & Data Integrity

- [x] **Global Layout (Sidebar):** Redesign base.html.twig with a professional navigation (Dashboard, Clients, Invoices, Settings).
- [x] **Freelance Dashboard:** Create the DashboardController for the user’s home page.
- [x] **Vanilla JS Design System:** Integrate the “Glassmorphism” dashboard structure with native JS logic for the sidebar and dropdown menus.
- [x] **Stimulus Refactor:** Convert the Vanilla JS into a layout_controller.js Stimulus controller for a robust architecture.
- [x] **Basic Entity Validation:** Add essential server‑side validation rules (NotBlank, Email, Length, UniqueEntity) to stabilize the Client CRUD.

### Phase 5: Invoicing Engine (Backend & Logic)

- [x] **Security Voters:** Implement `ClientVoter` for multi‑tenant data isolation.
- [x] **Access Control:** Finalize security.yaml to protect all routes requiring authentication.
- [x] **Entity Refactor:** Add a direct `User` relation to the `Invoice` entity.
- [x] **Invoice Subject:** Add `project_title` to the `Invoice` entity to group line items under a project.
- [x] **Invoice CRUD:** Generate the creation, editing, viewing, and deletion pages for invoices.
- [x] **Security Voter:** Update `InvoiceVoter` to use direct user ownership.
- [x] **Numbering Service:** Implement `InvoiceNumberGenerator` (e.g., FF-2026-001).

### Phase 6 : Facturation Dynamique (Interface UI)

- [x] **Financial Service:** Implement `InvoiceCalculator` for subtotal, VAT, and totals.
- [x] **Lifecycle Logic:** Implement DRAFT vs PAID statuses. Ensure PAID invoices are immutable (locked).
- [x] **The Snapshot** System: Add columns to the Invoice entity to "freeze" client personal data
- [x] **URSSAF Estimator:** Service to compute the 21.2% charge for the dashboard view.
- [x] **Invoice Form:** Create the main form and the InvoiceItem sub-form using Symfony `InvoiceItemType`.
- [x] **Stimulus.js:** "Add/Remove Line Item"
- [x] **Live Totals:** Real-time JS updates so the user sees the price change as they type.

### Phase 7 : Design Documentaire & Export

- [x] **Dashboard Integration:** Move invoice pages into the dashboard layout (same as Clients).
- [x] **Routing Integration:** Add invoices to the sidebar + secure access with InvoiceVoter.
- [x] **Tailwind Styling:** Apply full UI styling to the invoice form and line items.
- [x] **HTML Template:** Professional layout including "Art. 293B" legal mentions.
- [x] **Invoice Preview** Page: Read-only HTML view before PDF export.
- [x] **PDF Engine:** Integration of DomPDF for document generation.
- [x] **Secure Export:** Protected routes for PDF downloads.

### Phase 8: Dashboard Insights (Analytics)

- [x] **KPI Widgets:** Monthly/Yearly revenue tracking.
- [x] **Ceiling Tracker:** Progress bar for auto-entrepreneur revenue limits.
- [x] **Activity Feed:** Quick view of pending/overdue invoices.

### Phase 9: UX Optimization & Polish

- [x] **Flash Message System:** Professional toast notifications for CRUD actions (Success/Error).
- [x] **Empty State Designs:** Create high-quality placeholder views for users with 0 clients or invoices.
- [x] **Navigation Refinement:** Add "Active" states to sidebar links to show the user where they are.
- [x] **Form Validation:** Enhance client-side and server-side error messaging for better user guidance.

### Phase 10: Performance, Auth Enhancements & Deployment Readiness

- [x] **SQL Query Tuning:** Implemented Eager Loading (JOIN) to eliminate N+1 query issues.
- [x] **Fixture Integrity:** Deferred persist and `ReflectionProperty` usage for realistic data history.
- [x] **Profile Workflow:** Separated `/profile` (read-only) and `/profile/edit` with Turbo form validation.
- [x] **UI Styling:** Full "Glassmorphism" restyling of the user profile forms.
- [x] **Legal Verification:** Art. 293B (VAT) mentions + automated SIRET/VAT logic.
- [x] **Live Search:** Multi-criteria search (Invoice #, Client, Project) using `LiveProp`.
- [x] **Invoice Filtering:** Dynamic filtering by status (Draft, Sent, Paid, Overdue)
- [x] **Asynchronous Pagination** for both Client and Invoice lists using Live Components.
- [x] **Internationalization (i18n):** Implement EN/FR support across the UI (Translations, Localized Routing, and Locale Switcher).
- [x] **Asynchronous Pagination** Client  lists using Live Components.

---

## Phase 11: The Launch (Immediate Priority)

**Goal:** Deploy a fully functional portfolio to an online server with a “One‑Click Demo” for recruiters.

### A. Guest Mode (Recruiter Demo Access)

- [ ] **Demo Data Generator:** Create a service that generates a temporary user with realistic fake data.
- [ ] **Guest Login Controller:** Auto‑create user → generate demo data → programmatic login → redirect to dashboard.
- [ ] **Frontend Button:** Add a “Recruiter Access” button on the login page.

### B. Production Deployment (AlwaysData)

- [ ] **Hosting Setup:** Create an account on a hosting platform and configure the project.
- [ ] **Server Secrets:** Add `.env.local` with `APP_ENV=prod` and MariaDB credentials.
- [ ] **Database Init:** Run migrations or schema updates.
- [ ] **Assets Build:** Compile assets (`asset-map:compile`) for Tailwind v4.
- [ ] **Live Check:** Test the production URL on desktop and mobile.

### C. Documentation

- [ ] **README Update:**
- Add the **LIVE DEMO** link at the top.
- Include 1–2 dashboard screenshots.
- Add a clean feature list summarizing Phases 1–10.

---

---

## PARTT 2: VERSION 2 (Post-Launch - Planned Evolutions)

Focus: Professional polish, security, and advanced features.  
These tasks will be completed after the public launch and while applying for jobs/freelance missions.

## Phase - 12: Security & Compliance

- [ ] **Email Verification:** Implement post‑registration verification via `VerifyEmailBundle` (Mailtrap for testing).
- [ ] **Password Reset:** Secure reset flows with expiring tokens and styled email templates.
- [ ] **Remember Me:** Secure persistent login with token rotation.
- [ ] **GDPR Deletion:** Allow users to delete their account and all associated data.
- [ ] **Production Hardening:** Add CSP/HSTS headers and secure production firewall rules.

## Phase -13: Advanced Features

- [ ] **Customizable Email Templates:** Glassmorphism‑styled HTML emails consistent with the dashboard UI.
- [ ] **Direct PDF Delivery:** Automated emailing of invoices to clients.
- [ ] **Sales Cycle:** Quote/estimate management + “Convert Quote to Invoice” workflow.
- [ ] **Online Payments:** Stripe integration with Webhooks for automatic “PAID” updates.

## Phase 14: The Professional Polish

- [ ] **Data Deletion Logic:** Full GDPR‑compliant account deletion.
- [ ] **Production Config:** Finalize `.env.local` secrets, CSP/HSTS, and secure headers.
- [ ] **Asset Pipeline:** Minification and compression via AssetMapper (`asset-map:compile`).
- [ ] **Project Documentation:** Update README with live demo link, architecture overview, and installation guide.

## Phase 15: Online Payments

- [ ] **Stripe Integration:** Credit card payment support.
- [ ] **Webhooks:** Auto‑update invoice status to “PAID” after successful Stripe events.

## Phase 16: API & DevOps

- [ ] **API Exposure:** REST API with JWT Authentication.
- [ ] **Containerization:** Add `docker-compose.yaml` for consistent production environments.
