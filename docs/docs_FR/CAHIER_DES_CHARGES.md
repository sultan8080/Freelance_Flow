
##  Cahier des Charges (FR)
---
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
---
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
---
### 3. Spécifications Techniques
* **Framework :** Symfony 7.4 LTS + PHP 8.2+
* **Base de données :** MySQL (Doctrine ORM)
* **Frontend :** Twig + **Symfony AssetMapper** + TailwindCSS
* **Génération PDF :** DomPDF
* **Auth :** SecurityBundle (Form Login)
* **Architecture :** Services spécialisés pour la logique métier (Logic Separation)
---

### Standards de Sécurité
* **Hachage des mots de passe :** Utilisation de `PasswordHasher` avec les algorithmes les plus récents (Sodium/Argon2id).
* **Protections Natives :** Protection contre les failles CSRF, XSS, et injections SQL via les composants natifs de Symfony.
* **Isolation des Données :** Cloisonnement strict entre les utilisateurs via les **Voters** ou les **Entity Listeners** garantissant qu'un utilisateur ne peut jamais accéder aux données d'un tiers.