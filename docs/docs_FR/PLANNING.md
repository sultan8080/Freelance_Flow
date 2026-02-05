# Plan de Développement — Freelance Flow

## Partie 1 : Le MVP (Minimum Viable Product)

_Objectif : Une application fonctionnelle pour gérer, calculer et éditer des factures._

### Phase 1 : Architecture & Authentification

- [x] Initialisation Symfony 7.4 Webapp.
- [x] Configuration de la base de données (MySQL).
- [x] Création de l'entité `User` et système de Login/Logout.
- [x] Génération du formulaire d'inscription (`RegistrationForm`).
- [x] Installation de Tailwind CSS via AssetMapper.

### Phase 2 : Modélisation des Données & Logique Métier

- [x] **Conception de base de données :** Créer et documenter le schéma de données (MCD, MLD, ERD) via mocodo.net.
- [x] **Mise à jour User :** Ajouter les informations professionnelles au Freelancer (Nom complet, Adresse, SIRET/TVA, Téléphone).
- [x] **Entité Client :** Créer l'entité + établir la relation ManyToOne avec User.
- [x] **Entité Invoice (Facture) :** Créer l'entité avec les champs (numéro, date, statut, date d'échéance).
- [x] **Entité InvoiceItem (Lignes) :** Créer les lignes de facture (description, quantité, prix unitaire).
- [x] **Sécurité des données (Voters) :** S'assurer qu'un Freelancer ne peut voir que ses propres clients et factures.

### Phase 3 : Implémentation - CRM & Profil Freelance

- [x] **Configuration des Rôles :** Définir la hiérarchie ROLE_ADMIN vs ROLE_USER dans le fichier security.yaml.
- [x] **Formulaire de Profil (UserType) :** Créer un formulaire pour les données professionnelles (SIRET, TVA, Nom de l'entreprise, Adresse).
- [x] **Paramètres du Compte :** Créer le SettingsController pour permettre aux freelances de compléter leur identité professionnelle.
- [x] **Formulaire Client (ClientType) :** Créer le formulaire pour ajouter et modifier des clients.
- [x] **CRUD Client :** Générer l'interface (Liste, Vue, Modification, Suppression) pour les clients.
- [x] **Filtrage de Base :** S'assurer que la liste n'affiche que les clients liés à l'utilisateur connecté (étape avant les Voters).

### Phase 4 : Interface Globale, Sécurité & Intégrité des Données

- [x] **Mise en page globale (Sidebar) :** Refondre `base.html.twig` avec une navigation professionnelle (Tableau de bord, Clients, Factures, Paramètres).
- [x] **Tableau de bord Freelance :** Créer le `DashboardController` pour la page d'accueil utilisateur.
- [x] **Système de Design Vanilla JS :** Intégrer la structure de tableau de bord "Glassmorphism" avec une logique native JS pour la barre latérale et les menus déroulants.
- [x] **Refactorisation Stimulus :** Conversion du JS Vanilla en contrôleur `layout_controller.js` pour une architecture robuste.
- [x] **Validation Basique des Entités :** Ajouter les règles essentielles de validation serveur (NotBlank, Email, Length, UniqueEntity) pour stabiliser le CRUD Client.

### Phase 5 : Moteur de Facturation (Backend & Logique)

- [x] **Security Voters :** Implémenter `ClientVoter` pour l’isolation des données multi‑locataires.
- [x] **Contrôle d’Accès :** Finaliser `security.yaml` pour protéger toutes les routes nécessitant une authentification.
- [x] **Refactorisation d’entité :** Ajouter une relation directe `User` à l’entité `Invoice`.
- [x] **Sujet de facture :** Ajouter `project_title` à l’entité `Invoice` pour regrouper les lignes par projet.
- [x] **CRUD Facture :** Générer les pages de création, modification, visualisation et suppression des factures.
- [x] **Security Voter :** Mettre à jour `InvoiceVoter` pour utiliser la propriété directe de l’utilisateur.
- [x] **Service de numérotation :** Implémenter `InvoiceNumberGenerator` (ex. FF-2026-001).

### Phase 6 : Facturation Dynamique (Interface UI)

- [x] **Service Financier :** Implémenter `InvoiceCalculator` pour le sous‑total, la TVA et les totaux.
- [x] **Logique de Cycle de Vie :** Implémenter les statuts DRAFT vs PAID. S’assurer que les factures PAID sont immuables (verrouillées).
- [x] **Le Système de Snapshot :** Ajouter des colonnes à l’entité Invoice pour "geler" les données client (Nom, Adresse, SIRET) et les totaux finaux au moment du paiement.
- [x] **Estimateur URSSAF :** Service pour calculer la charge de 21.2% pour la vue tableau de bord.
- [x] **Formulaire de Facture :** Créer le formulaire principal et le sous‑formulaire InvoiceItem en utilisant Symfony `InvoiceItemType`.
- [x] **Stimulus.js :** "Ajouter/Supprimer une ligne" .
- [x] **Totaux en Direct :** Mise à jour en temps réel via JS pour que l’utilisateur voie le prix changer pendant la saisie.

### Phase 7 : Design Documentaire & Export

- [x] **Intégration au Dashboard :** Déplacer les pages de factures dans le layout du tableau de bord (comme pour Clients).
- [x] **Intégration du Routing :** Ajouter les factures dans la barre latérale + sécuriser l’accès avec InvoiceVoter.
- [x] **Style Tailwind :** Appliquer le style UI complet au formulaire de facture et aux lignes d’articles.
- [x] **Template HTML :** Mise en page professionnelle incluant les mentions légales "Art. 293B".
- [x] **Page d’Aperçu de Facture :** Vue HTML en lecture seule avant l’export PDF.
- [x] **Moteur PDF :** Intégration de DomPDF pour la génération de documents.
- [x] **Export Sécurisé :** Routes protégées pour les téléchargements PDF.

### Phase 8 : Insights & Statistiques (Analytics)

- [x] **Widgets KPI :** Suivi du chiffre d'affaires mensuel et annuel.
- [x] **Suivi Plafond :** Barre de progression pour les limites de l'auto-entrepreneur.
- [x] **Flux d'Activité :** Vue rapide des factures en attente ou en retard.

### Phase 9 : Peaufinage & Optimisation UX

- [x] **Système de Messages Flash :** Notifications "toast" professionnelles pour les actions CRUD.
- [x] **Design des "Empty States" :** Création de vues d'attente soignées pour les listes vides (0 clients/factures).
- [x] **Navigation Affinée :** Ajout d'états "actifs" sur la sidebar pour indiquer la page courante.
- [x] **Validation des Formulaires :** Amélioration des messages d'erreur côté client et serveur.


### Phase 10 : Performance, Authentification & Préparation au Déploiement

- [x] **Optimisation SQL :** Mise en place de l'Eager Loading (JOIN) pour éliminer les requêtes N+1.
- [x] **Intégrité des Fixtures :** Utilisation de la persistance différée et de `ReflectionProperty` pour un historique de données réaliste.
- [x] **Workflow Profil :** Séparation `/profile` (lecture seule) et `/profile/edit` avec validation de formulaire Turbo.
- [x] **Design UI :** Refonte complète des formulaires de profil utilisateur en style "Glassmorphism".
- [x] **Vérification Légale :** Mentions Art. 293B (TVA) + logique automatisée SIRET/TVA.
- [x] **Recherche Live :** Recherche multicritères (N° Facture, Client, Projet) via `LiveProp`.
- [x] **Filtrage des Factures :** Filtrage dynamique par statut (Brouillon, Envoyée, Payée, Retard).
- [x] **Pagination Asynchrone** pour les listes Clients et Factures via Live Components.
---

## Partie 2 : Version 2 (Évolutions Planifiées)

### Phase 11 : UX Avancée & Préparation au Déploiement

#### Partie A : Communication & Sécurité (Fonctionnalités)

- [x] **Internationalisation (i18n) :** Mise en place du support EN/FR (Traductions, Routage localisé et sélecteur de langue).
- [ ] **Vérification d'Email :** Mise en œuvre de la vérification post-inscription via `VerifyEmailBundle` (Config Mailtrap pour les tests).
- [ ] **Réinitialisation de Mot de Passe :** Flux sécurisé avec jetons expirables et templates d'emails stylisés.
- [ ] **"Se souvenir de moi" :** Activation du "Remember Me" sécurisé avec rotation des jetons.
- [ ] **Envoi Direct de PDF :** Envoi automatisé des factures aux clients par email.
- [ ] **Templates d'Email Personnalisés :** Création d'emails HTML en style Glassmorphism cohérents avec l'interface.

#### Partie B : Finitions Professionnelles

- [ ] **Suppression de Données :** Permettre aux utilisateurs de supprimer leur compte et leurs données en toute sécurité (conformité RGPD).
- [ ] **Configuration de Production :** Paramétrage des secrets `.env.local`, mise en place du CSP/HSTS et des headers de sécurité.
- [ ] **Optimisation des Assets :** Finalisation de la minification et compression via AssetMapper (`asset-map:compile`).
- [ ] **Documentation du Projet :** Rédaction d'un `README.md` complet avec lien de démo live, architecture technique et guide d'installation.

### Phase 12 : Cycle de Vente (Devis)

- [ ] **Gestion des Devis :** Création et suivi des estimations/devis.
- [ ] **Logique de Conversion :** Conversion d'un devis en facture en un seul clic.

### Phase 13 : Paiements en Ligne

- [ ] **Intégration Stripe :** Mise en place de la passerelle pour les paiements par carte bancaire.
- [ ] **Webhooks :** Mise à jour automatique du statut "Payée" lors d'une transaction réussie.

### Phase 14 : API & DevOps

- [ ] **Exposition API :** Accès aux données via une API REST (Authentification JWT).
- [ ] **Conteneurisation :** Configuration Docker pour des environnements de production identiques.
