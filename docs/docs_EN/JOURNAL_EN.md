
#  Development Log — Freelance Flow [English version]

## 📅 Day 4 [January 19, 2026] - Global UI & Security  (Phase 4)
- [x] **Project Management:** Finalized the MVP roadmap (Phases 4-10) and updated project documentation.




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
* **Attribute-Based Security:** Chose `#[IsGranted]` over `security.yaml` for granular, easy-to-read access control.
* **Unified Redirects:** Simplified the UX by ensuring both Login and Registration land on the same dashboard.
* **Localization:** Switched the entire interface to English to align with a global SaaS standard.

---

### **Day 0: January 15, 2026 — Initialization & Scoping**

#### Current Status
* **Phase 0:** Completed (Scoping & Environment setup).
* **Next Step:** Phase 1 — User entity creation and Authentication system.

#### Work Completed
- [x] **Technical Initialization:** Project created using Symfony 7.4 (`webapp` pack).
- [x] **Frontend Setup:** Installed **Symfony AssetMapper** and the **Tailwind Bundle**. 
- [x] **Documentation:** Drafted full bilingual project specifications and technical requirements.
- [x] **Planning:** Defined a precise **10-phase roadmap** for the MVP and V2.
- [x] **Versioning:** Git repository configured and initial push to GitHub.

#### Technical Decisions
* **No-Node Architecture:** Opted for `symfony/asset-mapper` and `symfonycasts/tailwind-bundle` to remove Node.js/npm dependencies. This leads to a lighter, more modern developer experience.
* **Security by Design:** Decision to implement **Symfony Voters** early in the process to ensure data isolation per user.

