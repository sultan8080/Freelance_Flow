# 🎯 Project Specifications — FreelanceFlow

## 1. Core Features (MVP)
* **User Management:** Secure user registration, login, and session management.
* **Simplified CRM:** Complete client management (Create, Read, Update, Delete) with search functionality.
* **Invoicing Engine:**
    * Dynamic invoice creation with line-by-line item management.
    * Automated total calculations (Tax-exempt: "TVA non applicable, art. 293B du CGI").
    * Sequential automated invoice numbering (e.g., `FF-2026-001`).
* **Tax Intelligence:**
    * Automated URSSAF contribution estimates (based on 21.2% or 21.1% rates).
    * Revenue ceiling tracking for Micro-entrepreneurs (BNC/BIC limits).
* **Professional Export:** PDF generation for invoices including all mandatory legal mentions.
* **Dashboard:** Real-time data visualization of monthly/yearly revenue and tax provisions.
* **Security:** Implementation of **Symfony Voters** for granular access control (users can only access their own records).

---

## 2. Optional Features (V2+)
### Automation & Comfort
* **Emailing:** Send invoices directly to clients via **Symfony Mailer**.
* **Quotes (Estimates):** Create professional quotes with a "Convert to Invoice" feature.
* **Reminders:** Automated notifications for overdue payments.

### Payments & API
* **Stripe Integration:** Integrated payment links on invoices for instant settlement.
* **REST API:** Fully documented API with **LexikJWT** for future mobile app development.

###  Export & Quality
* **Data Export:** CSV/Excel formats for official tax declarations.
* **Quality Assurance:** Automated testing suite using **PHPUnit & Panther**.
* **Containerization:** Full **Docker** setup for production-ready environments.

---

## 3. Technical Stack
* **Framework:** Symfony 7.4 LTS + PHP 8.2+
* **Database:** MySQL (via Doctrine ORM)
* **Frontend:** Twig + **Symfony AssetMapper** + TailwindCSS
* **PDF Generation:** DomPDF
* **Authentication:** SecurityBundle (Form-based login)
* **Architecture:** Service-Oriented Architecture (Logic separation for tax and business rules)

---

## 4. Security Standards
* Passwords hashed using **PasswordHasher** (Sodium/Argon2id).
* Protection against CSRF, XSS, and SQL Injection (native Symfony features).
* Strict data isolation between users via **Entity Listeners** or **Voters**.