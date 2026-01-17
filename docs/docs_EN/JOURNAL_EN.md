
#  Development Log — FreelanceFlow

## EN English

> **Current Status:** Phase 1 Complete
> **Next Milestone:** Phase 2 Entity & CRUD Management


## 📅 Day 1: January 16, 2026 — Auth, Security & UI (Phase 1)

### Work Completed
- [x] **Auth System:** Generated `User` entity, `LoginFormAuthenticator`, and `SecurityController`.
- [x] **Database:** Configured MariaDB 10.4.32 compatibility and ran initial migrations.
- [x] **Security:** Implemented `#[IsGranted('ROLE_USER')]` to protect the `DashboardController`.
- [x] **UI/UX:** Styled them with Tailwind CSS.
- [x] **Flow:** Unified redirect logic in the Authenticator to point to `dashboard_freelancer`.
- [x] **Theme Integration:** Implemented Tailwind v4 semantic variables (`primary`, `app-bg`) across Login and Registration templates for a unified Design System.

### Phase 2: Data Modeling & Business Logic
- [x] **Data Design:** Created and documented the database schema (MCD, MLD, ERD) in `docs/database/`.

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

