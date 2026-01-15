# FreelanceFlow

**Gestionnaire de facturation et fiscalité pour auto-entrepreneurs.**
*Billing and tax management for French freelancers.*

---

## 📂 Documentation

### Version Française
* [**Cahier des Charges**](#cahier-des-charges) (Ci-dessous)
* [**Roadmap du Projet**](./PLANNING.md) : Les phases de développement et objectifs.
* [**Journal de Bord**](./JOURNAL_FR.md) : Suivi technique et décisions.

### English Version
* [**Project Specifications**](./SPECIFICATIONS.md) : Technical and business requirements.
* [**Project Roadmap**](./WORK_PLAN.md) : Development phases and milestones.
* [**Development Journal**](./JOURNAL_EN.md) : Daily technical logs and choices.

---

##  Cahier des Charges (FR)
### 1. Fonctionnalités Cœur
* **Gestion Utilisateur :** Inscription, connexion et déconnexion sécurisée.
* **CRM Simplifié :** Gestion complète des clients (Ajout, modification, suppression, recherche).
* **Moteur de Facturation :**
    * Création de factures avec ajout dynamique de lignes (Items).
    * Calcul automatique du total HT (Auto-entrepreneur : TVA non applicable, art. 293B du CGI).
    * Numérotation chronologique automatisée (ex: `FF-2026-001`).
* **Intelligence Fiscale :**
    * Service de calcul des cotisations URSSAF (taux de 21.2%).
    * Suivi du plafond de chiffre d'affaires (Micro-BNC/BIC).
* **Export Professionnel :** Génération de factures au format PDF avec mentions légales obligatoires.
* **Dashboard :** Visualisation du CA mensuel/annuel et des cotisations provisionnées.
* **Sécurité :** **Symfony Voters** pour garantir qu'un freelance ne voit que ses propres données.

### 2. Fonctionnalités Optionnelles (V2+)
* **Automatisation & Confort :**
    * Envoi de factures par email via **Symfony Mailer**.
    * Gestion des **Devis** avec fonction "Convertir en Facture".
    * Relances automatiques pour les factures en retard.
* **Paiements & API :**
    * Intégration **Stripe** pour paiement direct.
    * API REST avec **LexikJWT** pour future application mobile.
* **Export & Qualité :**
    * Export comptable (CSV/Excel) pour déclaration URSSAF.
    * Tests automatisés avec **PHPUnit & Panther**.
    * Conteneurisation complète avec **Docker**.

### 3. Spécifications Techniques
* **Framework :** Symfony 7.4 LTS + PHP 8.2+
* **Base de données :** MySQL (Doctrine ORM)
* **Frontend :** Twig + **Symfony AssetMapper** + TailwindCSS
* **Génération PDF :** DomPDF
* **Auth :** SecurityBundle (Form Login)
* **Architecture :** Services spécialisés pour la logique métier (Logic Separation)