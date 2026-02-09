# Freelance Lucid 

[![Symfony 7](https://img.shields.io/badge/Symfony-7.4-black?style=for-the-badge&logo=symfony)](https://symfony.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.1.11-38bdf8?style=for-the-badge&logo=tailwindcss)](https://tailwindcss.com)
[![MariaDB](https://img.shields.io/badge/MariaDB-Database-brown?style=for-the-badge&logo=mariadb)](https://mariadb.org)
[![Live Demo](https://img.shields.io/badge/🟢_Live_Demo-Online-success?style=for-the-badge)](https://freelance-lucid.alwaysdata.net/)

> **A production-ready SaaS for freelancers to manage clients, invoices, and financial analytics with strict data isolation.**

---

## 🌐 Live Demo & Recruiter Access

**[👉 Click here to launch the Live Project](https://freelance-lucid.alwaysdata.net/)**

To make it easy to explore without creating an account:
1. Go to the **Login Page**.
2. Click the rocket button **"Recruiter Access (One-Click)"**.
3. The system will automatically generate a secure guest account, populate it with realistic demo data, and log you in instantly.

---
## 📸 Interface Preview

<p align="center">
  <b>Main Analytics Dashboard (Desktop)</b><br>
  <img src="https://github.com/user-attachments/assets/78ad3f3b-af1c-438c-b225-1b8c438d26db" width="850" alt="Dashboard Desktop">
</p>

<br>

<p align="center">
  <b>Invoice Management & Financial Insights (Mobile)</b><br>
  <img src="https://github.com/user-attachments/assets/c4516996-a38a-4db3-b261-82263707ae6d" width="450" alt="Invoice Detail" style="vertical-align: middle;">
  &nbsp;&nbsp;&nbsp;&nbsp;
  <img src="https://github.com/user-attachments/assets/9515caec-f144-4450-89af-2279261792fb" width="250" alt="Mobile View" style="vertical-align: middle;">
</p>

---

## ✨ Key Features (Phases 1–10)

This Minimum Viable Product (MVP) was built in **11 distinct phases**, simulating a real-world software development lifecycle:

### 📊 1. Smart Analytics
* **Real-Time KPIs:** Tracks Total Revenue, Pending Invoices, and Monthly Growth.
* **Interactive Visualization:** Chart.js integration wrapped in Stimulus controllers for dynamic rendering.
* **Time-Frame Filtering:** Toggle analytics between different periods.

### 💰 2. Financial Engine
* **Complete Workflow:** Create Quotes → Convert to Invoices → Mark as Paid.
* **State Management:** Robust status logic (Draft, Sent, Paid, Cancelled) to prevent accounting errors.
* **Service Catalog:** Reusable service/product items for rapid invoicing.

### 👥 3. Client Management (CRM)
* **Client Directory:** specific pricing and contact details for each customer.
* **Search & Filter:** Instant search capabilities for managing client lists.

### 🔒 4. Architecture & Security
* **Multi-Tenant Data Isolation:** Strict Database logic ensures users can only access their own data.
* **Secure Auth:** Symfony Security Bundle with Bcrypt password hashing.
* **GDPR Compliance:** Dedicated Terms & Privacy pages with legal structure.

### 🎨 5. Modern UX/UI
* **Glassmorphism Design:** Custom Tailwind CSS configuration for a translucent, modern aesthetic.
* **Mobile First:** Fully responsive navigation and layouts optimized for smartphones.
* **Feedback System:** Flash notifications for all user actions (Success/Error).

## 📂 Documentation

This MVP project demonstrates **Structured Engineering Methodology** beyond just coding.
💡 Click the links below to access the full documentation.

### Version Française
* [**Cahier des Charges**](./docs/docs_FR/CAHIER_DES_CHARGES.md) : Besoins métiers et fonctionnalités.
* [**Roadmap du Projet**](./docs/docs_FR/PLANNING.md) : Les phases de développement et objectifs.
* [**Journal de Bord**](./docs/docs_FR/JOURNAL_FR.md) : Suivi technique et décisions (Daily Log).

### English Version
* [**Project Specifications**](./docs/docs_EN/SPECIFICATIONS.md) : Technical and business requirements.
* [**Project Roadmap**](./docs/docs_EN/WORK_PLAN.md) : Development phases and milestones.
* [**Development Journal**](./docs/docs_EN/JOURNAL_EN.md) : Daily technical logs and architectural choices.

## 🏗️ Database Architecture (MCD)

The database was designed using the **Merise method** to guarantee data integrity and strict isolation.
**Conceptual Data Model (Modèle conceptuel de données - MCD):**

![Database Diagram/Schema](./docs/database_schema/MCD.png)

> 💡 [Click here to see Modèle logique de données (MLD)](./docs/database_schema/MLD.md)


---

## 🛠 Tech Stack

* **Backend:** Symfony 7.4 (PHP 8.2+)
* **Database:** MariaDB (Doctrine ORM)
* **Frontend:** Twig + TailwindCSS + Stimulus.js (AssetMapper / No Node.js dependency)
* **Deployment:** Linux (Debian), Apache, SSH Pipeline
* **Security:** Symfony Security Bundle + Custom Voters
