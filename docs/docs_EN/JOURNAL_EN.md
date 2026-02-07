# Development Log — Freelance Lucid [English version]

## 📅 Day 12–13 [February 3–4, 2026] — Performance, Auth Enhancements & Deployment Readiness (Phase 10)

### Accomplishments:

- [x] **SQL Query Tuning:** Eliminated N+1 issues via eager loading (JOIN).
- [x] **Fixture Integrity:** Deferred persist + `ReflectionProperty` for realistic historical data.
- [x] **Profile Workflow:** Split `/profile` (read-only) and `/profile/edit` with Turbo validation.
- [x] **UI Styling:** Applied full glassmorphism restyling to profile forms.
- [x] **Legal Verification:** Added Art. 293B mentions + automated SIRET/VAT validation logic.
- [x] **Live Search:** Multi-criteria search (Invoice #, Client, Project) using `LiveProp`.
- [x] **Invoice Filtering:** Dynamic filtering by status (Draft, Sent, Paid, Overdue).
- [x] **Asynchronous Pagination:** Live Component–based async pagination for Clients and Invoices.


## 📅 Day 11 [February 2, 2026] — UX Optimization & Polish (Phase 9)

### Accomplishments:

- [x] **Flash Message System:** Built a professional "Toast" notification system for CRUD actions, ensuring clear visual feedback for Success/Error states.
- [x] **Empty State Designs:** Designed and integrated high-quality placeholder illustrations and "Call to Action" cards for users with no clients or invoices yet.
- [x] **Navigation Refinement:** Added dynamic "Active" state logic to the sidebar links using Request attributes to improve user orientation.
- [x] **Form Validation UX:** Enhanced error rendering for both client-side and server-side validation, ensuring specific fields highlight clearly when inputs are invalid.
- [x] **Responsive Polish:** Finalized mobile-specific UI adjustments for the bento-style cards on the dashboard.

### 🏁 Current Project Status: Phase 9 Complete

**Key Milestone:** The application is now "User-Ready." Beyond just CRUD functionality, the platform now provides actionable financial insights and a polished, professional interface that handles empty states and errors gracefully.

## 📅 Day 10 [February 1, 2026] — Dashboard Insights & Analytics (Phase 8)

### Accomplishments:

- [x] **KPI Widgets:** Developed real-time revenue tracking logic to calculate Monthly and Yearly turnover directly from the `Invoice` database.
- [x] **Ceiling Tracker:** Implemented a visual progress bar for Auto-Entrepreneur revenue limits (micro-entreprise ceilings), helping users monitor their tax thresholds.
- [x] **Activity Feed:** Built a "Quick View" component to display the most recent pending or overdue invoices, prioritized by due date.
- [x] **Data Aggregation:** Optimized DQL queries in `InvoiceRepository` to fetch financial summaries efficiently for the dashboard view.

## 📅 Day 7-9 [Jan 24 – Jan 31, 2026] - UI, Dynamic Logic & PDF Export (Phases 6 & 7)

_Note: This period included a strategic 2-day break to manage burnout; development resumed with a focus on core financial logic, the data snapshot system, and complex PDF/JS integrations_

### Accomplishments:

#### Phase 6: Facturation Dynamique (Interface UI)

- [x] **Financial Logic:** Developed `InvoiceCalculator` for subtotal and VAT calculations.
- [x] **Immutability System:** Implemented `DRAFT`/`PAID` statuses and a **Snapshot System** to freeze client data (legal requirement).
- [x] **Tax Estimation:** Built the **URSSAF Estimator** (21.2%) for dashboard projections.
- [x] **Interactive UI:** - Integrated **Stimulus.js** for dynamic "Add/Remove" line items.
    - Added **Live Totals** to update pricing in real-time via JavaScript.

#### Phase 7: Design Documentaire & Export

- [x] **PDF Engine:** Integrated **DomPDF** to generate professional invoices.
- [x] **Legal Compliance:** Created HTML templates with mandatory "Art. 293B" mentions.
- [x] **Dashboard Integration:** Finalized Tailwind CSS styling for the invoice module and sidebar.
- [x] **Secure Export:** Protected PDF download routes using `InvoiceVoter`.

---

### 🏁 Final Project Status: Phase 7 Complete

**Total Duration:** 9 Productive Days (within a 10-day calendar window).  
**Key Learning:** Managing state in nested Symfony forms with Stimulus.js requires careful event handling, but results in a much better User Experience.

## 📅 Day 6 [January 23, 2026] - Invoicing Engine - Backend & Logic (Phase 5)

### Accomplishments:

- [x] **Security Voters:** Implemented ClientVoter to ensure strict multi‑tenant data isolation.
- [x] **Access Control:** Finalized security.yaml to secure all authenticated routes.
- [x] **Entity Refactor:** Added a direct User → Invoice relation for clear ownership handling.
- [x] **Invoice Subject:** Added project_title to group invoice line items by project.
- [x] **Invoice CRUD:** Built full create, edit, view, and delete pages for invoices.
- [x] **InvoiceVoter:** Updated authorization logic to rely on direct user ownership.
- [x] **Numbering Service:** Implemented InvoiceNumberGenerator (e.g., FF‑2026‑001) for automatic, sequential numbering.

## 📅 Day 5 [January 20, 2026] - Global UI & Security (Phase 4)

### Technical Fixes:

- [x] **Tailwind v4 Utility Conflict:** Fixed an issue where hidden white space nowrap was failing.
- [x] **Turbo Frame Debugging:** Resolved a "Save" button failure caused by Turbo Frame interception; corrected form targets to ensure proper state updates.
- [x] **Form Validation Feedback:** Fixed missing error rendering in Twig templates; ensured Symfony validation messages appear consistently.
- [x] **Flash Messages Reliability:** Corrected a bug where success/error flashes were not displayed due to missing Turbo‑friendly markup.
- [x] **Dropdown State Bug:** Resolved an issue where the user dropdown stayed open after navigation under Turbo.
- [x] **Mobile Sidebar Overlay:** Fixed a z-index conflict causing the overlay to appear behind content on small screens.

### Accomplishments:

- [x] **Dynamic Navigation:** Configured Twig logic to detect active routes (app.request.get('\_route')) and apply Indigo highlighting to navigation links (Dashboard, Clients).
- [x] **Pro Architecture:** Separated code into base.html.twig (shell) and index.html.twig (content) to ensure scalable template inheritance.
- [x] **startStimulusApp** Migrated UI logic to a Stimulus controller to ensure smooth navigation and full Turbo compatibility.
- [x] **Architecture Redesign:** Overhauled base.html.twig to implement a high-end Sidebar navigation. Integrated Glassmorphism aesthetics using Tailwind v4.

## 📅 Day 4 [January 19, 2026] - Global UI & Security (Phase 4)

### Accomplishments:

- [x] **Project Management:** Finalized the MVP roadmap (Phases 4-10) and updated project documentation.
- [x] **Master Layout Refactor:** Converted base.html.twig into a robust dashboard structure featuring a fixed, responsive sidebar.
- [x] **Glass Design System:** Implemented transparency and blur effects (backdrop-blur) using Tailwind v4 utility classes and custom theme variables (rounded-bento).
- [x] **Native JS Logic:** Developed a custom Vanilla JS script to handle the burger menu toggle, dynamic logo visibility, and user dropdowns

## 📅 Day 3 [January 18, 2026] - Profile, Settings, and Client CRUD (Phase 3)

### Accomplishments:

- [x] **Settings & Profile:** Created `SettingsController` and implemented user profile updates (Names, Email).
- [x] **Client Security:** Generated a secured Client CRUD. Data is strictly filtered by the logged-in user (`getUser`).
- [x] **Business Logic:** Automated user assignment for new clients and protected `show/edit/delete` routes via ownership verification.
- [x] **Tailwind v4 Infrastructure:** - Debugged and resolved the "No Styles" issue by implementing the `@source` directive in `app.css`.
    - Performed a deep system purge of `var/tailwind` and `asset-map` to fix cache persistence.
- [x] **UI Modernization:**
    - **Responsive Index:** Built a SaaS-style table that hides secondary columns (Email/Contact) on mobile but keeps "Actions" visible.
    - **Form Design:** Created a professional two-column grid for New/Edit forms with Indigo/Slate theme variables.
    - **Profile View:** Implemented a "Show" page with a profile header, generated avatar initials, and structured data grids.

## 📅 Day 2: 2026-01-17 - Finalizing Design & Technical Initialization (Phase 2)

### Accomplishments:

- [x] **Folder Architecture:** Reorganized documentation into `/docs/docs_FR`, `/docs/docs_EN`, and `/docs/database`.
- [x] **Main README.md:** Updated with a professional project overview and embedded MCD (SVG) diagram.
- [x] **User Entity:** Enriched with legal fields (SIRET, VAT, address) and identity attributes (FirstName, LastName).
- [x] **Automation (Traits):** Created and integrated `TimestampableTrait` to automatically handle `createdAt` and `updatedAt`.
- [x] **Technical Quality:** Deleted obsolete migrations to consolidate a clean "Master Migration" and enabled `HasLifecycleCallbacks`.
- [x] **Database:** Performed a full reset and successfully synchronized the relational schema.
- [x] **Invoice Logic:** Updated the constructor to initialize 'draft' status and set `dueDate` to +30 days by default.
- [x] **Automation:** Added a trigger in `setStatus` to automatically fill `paidAt` when marked as paid.
- [x] **Advanced Fixtures:** Implemented nested loops to generate 5 Users, 25 Clients, 125 Invoices, and 625 InvoiceItems.
- [x] **Validation:** Verified cascading relationships and data consistency through automated fixtures.

## 📅 Day 1: January 16, 2026 — Auth, Security & UI (Phase 1)

### Work Completed

- [x] **Auth System:** Generated `User` entity, `LoginFormAuthenticator`, and `SecurityController`.
- [x] **Database:** Configured MariaDB 10.4.32 compatibility and ran initial migrations.
- [x] **Security:** Implemented `#[IsGranted('ROLE_USER')]` to protect the `DashboardController`.
- [x] **UI/UX:** Styled them with Tailwind CSS.
- [x] **Flow:** Unified redirect logic in the Authenticator to point to `dashboard_freelancer`.
- [x] **Theme Integration:** Implemented Tailwind v4 semantic variables (`primary`, `app-bg`) across Login and Registration templates for a unified Design System.

### Technical Decisions

- **Attribute-Based Security:** Chose `#[IsGranted]` over `security.yaml` for granular, easy-to-read access control.
- **Unified Redirects:** Simplified the UX by ensuring both Login and Registration land on the same dashboard.
- **Localization:** Switched the entire interface to English to align with a global SaaS standard.

---

### **Day 0: January 15, 2026 — Initialization & Scoping**

#### Current Status

- **Phase 0:** Completed (Scoping & Environment setup).
- **Next Step:** Phase 1 — User entity creation and Authentication system.

#### Work Completed

- [x] **Technical Initialization:** Project created using Symfony 7.4 (`webapp` pack).
- [x] **Frontend Setup:** Installed **Symfony AssetMapper** and the **Tailwind Bundle**.
- [x] **Documentation:** Drafted full bilingual project specifications and technical requirements.
- [x] **Planning:** Defined a precise **10-phase roadmap** for the MVP and V2.
- [x] **Versioning:** Git repository configured and initial push to GitHub.

#### Technical Decisions

- **No-Node Architecture:** Opted for `symfony/asset-mapper` and `symfonycasts/tailwind-bundle` to remove Node.js/npm dependencies. This leads to a lighter, more modern developer experience.
- **Security by Design:** Decision to implement **Symfony Voters** early in the process to ensure data isolation per user.
