# Plan de Développement — Freelance Flow

## Partie 1 : Le MVP (Minimum Viable Product)
*Objectif : Une application fonctionnelle pour gérer, calculer et éditer des factures.*

### Phase 1 : Architecture & Authentification
- [x] Initialisation Symfony 7.4 Webapp.
- [x] Configuration de la base de données (MySQL).
- [x] Création de l'entité `User` et système de Login/Logout.
- [x] Génération du formulaire d'inscription (`RegistrationForm`).
- [x] Installation de Tailwind CSS via AssetMapper.

### Phase 2 : Modélisation des Données & Logique Métier
- [x] **Conception de base de données :** Créer et documenter le schéma de données (MCD, MLD, ERD) via mocodo.net.
- [x] Mise à jour User : Ajouter les informations professionnelles au Freelancer (Nom complet, Adresse, SIRET/TVA, Téléphone).
- [x] Entité Client : Créer l'entité + établir la relation ManyToOne avec User.
- [x] Entité Invoice (Facture) : Créer l'entité avec les champs (numéro, date, statut, date d'échéance).
- [x] Entité InvoiceItem (Lignes) : Créer les lignes de facture (description, quantité, prix unitaire).
- [x] Sécurité des données (Voters) : S'assurer qu'un Freelancer ne peut voir que ses propres clients et factures.

### Phase 3 : Implémentation - CRM & Profil Freelance
- [x] Configuration des Rôles : Définir la hiérarchie ROLE_ADMIN vs ROLE_USER dans le fichier security.yaml.
- [x] Formulaire de Profil (UserType) : Créer un formulaire pour les données professionnelles (SIRET, TVA, Nom de l'entreprise, Adresse).
- [x] Paramètres du Compte : Créer le SettingsController pour permettre aux freelances de compléter leur identité professionnelle.
- [x] Formulaire Client (ClientType) : Créer le formulaire pour ajouter et modifier des clients.
- [x] CRUD Client : Générer l'interface (Liste, Vue, Modification, Suppression) pour les clients.
- [x] Filtrage de Base : S'assurer que la liste n'affiche que les clients liés à l'utilisateur connecté (étape avant les Voters).


### Phase 4 : Interface Globale & Sécurité
- [ ] **Layout Sidebar :** Refonte du `base.html.twig` avec une navigation pro (Tableau de bord, Clients, Factures, Paramètres).
- [ ] **Tableau de Bord :** Création du `DashboardController` pour la page d'accueil.
- [ ] **Voters Symfony :** Implémentation de `ClientVoter` et `InvoiceVoter` pour l'isolation des données.
- [ ] **Contrôle d'Accès :** Finalisation du `security.yaml` pour protéger toutes les routes privées.

### Phase 5 : Moteur de Facturation (Backend & Logique)
- [ ] **Audit des Entités :** Vérification des relations entre `User`, `Client`, `Invoice`, et `InvoiceItem`.
- [ ] **Service de Numérotation :** Générateur de numéros de facture (ex: `FF-2026-001`).
- [ ] **Service Financier :** Logique de calcul du Sous-total, TVA, et Total TTC.
- [ ] **Estimateur URSSAF :** Calcul des charges sociales prévisionnelles (21.2%).

### Phase 6 : Facturation Dynamique (Interface UI)
- [ ] **Formulaire Facture :** Implémentation via Symfony `CollectionType`.
- [ ] **Stimulus.js :** Ajout/Suppression dynamique de lignes sans rechargement de page.
- [ ] **Totaux en Direct :** Calcul JavaScript des totaux en temps réel sur le formulaire.

### Phase 7 : Design Documentaire & Export
- [ ] **Template HTML :** Design pro incluant les mentions légales "Art. 293B".
- [ ] **Moteur PDF :** Intégration de DomPDF pour la génération de documents.
- [ ] **Export Sécurisé :** Routes protégées pour le téléchargement des PDF.

### Phase 8 : Insights & Statistiques (Analytics)
- [ ] **Widgets KPI :** Suivi du chiffre d'affaires mensuel et annuel.
- [ ] **Suivi Plafond :** Barre de progression pour les limites de l'auto-entrepreneur.
- [ ] **Flux d'Activité :** Vue rapide des factures en attente ou en retard.

### Phase 9 : Peaufinage & Optimisation UX
- [ ] **Système de Messages Flash :** Notifications "toast" professionnelles pour les actions CRUD.
- [ ] **Design des "Empty States" :** Création de vues d'attente soignées pour les listes vides (0 clients/factures).
- [ ] **Navigation Affinée :** Ajout d'états "actifs" sur la sidebar pour indiquer la page courante.
- [ ] **Validation des Formulaires :** Amélioration des messages d'erreur côté client et serveur.

### Phase 10 : Performance & Préparation Déploiement
- [ ] **Optimisation SQL :** Mise en place du "Eager Loading" (`JOIN`) pour supprimer les problèmes de requêtes N+1.
- [ ] **Vérification Légale :** Contrôle final des mentions obligatoires (SIRET, logique TVA, Art. 293B).
- [ ] **Configuration Prod :** Paramétrage du `.env.local`, des en-têtes de sécurité et compression des assets.
- [ ] **Documentation Finale :** Finalisation du `README.md` avec le guide d'installation et d'utilisation.

---

## Partie 2 : Version 2 (Évolutions)

### Phase 11 : Automatisation Email
- [ ] Envoi direct des factures PDF par email via Symfony Mailer.
- [ ] Templates d'email personnalisables.

### Phase 12 : Cycle de Vente Complet (Devis)
- [ ] Gestion des devis (Quotes).
- [ ] Transformation d'un devis en facture en un clic.

### Phase 13 : Paiement en Ligne
- [ ] Intégration Stripe pour le règlement par CB.
- [ ] Mise à jour automatique du statut "Payé".

### Phase 14 : API & DevOps
- [ ] Exposition des données via API REST (JWT).
- [ ] Conteneurisation Docker pour le déploiement.