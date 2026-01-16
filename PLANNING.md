# Plan de Développement — FreelanceFlow

## Partie 1 : Le MVP (Minimum Viable Product)
*Objectif : Une application fonctionnelle pour gérer, calculer et éditer des factures.*

### Phase 1 : Architecture & Authentification
- [ ] Initialisation Symfony 7.4 Webapp.
- [ ] Configuration de la base de données (MySQL).
- [ ] Création de l'entité `User` et système de Login/Logout.
- [ ] Génération du formulaire d'inscription (`RegistrationForm`).
- [ ] Installation de Tailwind CSS via AssetMapper.

### Phase 2 : Modélisation des Données & Logique Métier
- [ ] **Conception de base de données :** Créer et documenter le schéma de données (MCD, MLD, ERD) via mocodo.net.
- [ ] Mise à jour User : Ajouter les informations professionnelles au Freelancer (Nom complet, Adresse, SIRET/TVA, Téléphone).
- [ ] Entité Client : Créer l'entité + établir la relation ManyToOne avec User.
- [ ] Entité Invoice (Facture) : Créer l'entité avec les champs (numéro, date, statut, date d'échéance).
- [ ] Entité InvoiceItem (Lignes) : Créer les lignes de facture (description, quantité, prix unitaire).
- [ ] Sécurité des données (Voters) : S'assurer qu'un Freelancer ne peut voir que ses propres clients et factures.

### Phase 3 : CRM - Gestion des Clients
- [ ] Interface de liste des clients avec recherche.
- [ ] Formulaires de création et d'édition des clients.
- [ ] Suppression sécurisée des clients.

### Phase 4 : Sécurité & Isolation (Voters)
- [ ] Implémentation des **Symfony Voters** pour les Clients.
- [ ] Implémentation des **Symfony Voters** pour les Factures.
- [ ] *Garantie : Un utilisateur ne peut ni voir ni modifier les données d'un autre.*

### Phase 5 : Moteur de Facturation (Interface)
- [ ] Formulaire de création de facture.
- [ ] Ajout dynamique de lignes (Items) en JavaScript/Stimulus.
- [ ] Gestion des dates d'émission et d'échéance.

### Phase 6 : Intelligence Métier & Fiscalité
- [ ] Système de numérotation automatique (ex: `FF-2026-001`).
- [ ] Création du `TaxCalculatorService` pour les calculs HT.
- [ ] Calcul provisionnel des cotisations URSSAF (taux 21.2%).

### Phase 7 : Rendu & Design des Factures
- [ ] Design HTML/CSS d'un template de facture professionnel.
- [ ] Intégration des mentions légales (Art. 293B du CGI).
- [ ] Mise en page du pied de page (SIRET, Coordonnées).

### Phase 8 : Génération PDF
- [ ] Installation de DomPDF.
- [ ] Conversion du template HTML en PDF.
- [ ] Fonction de téléchargement sécurisé du fichier PDF.

### Phase 9 : Tableau de Bord (Dashboard)
- [ ] Statistiques du Chiffre d'Affaires (Mois/Année).
- [ ] Suivi visuel des plafonds de la micro-entreprise.
- [ ] Liste des dernières factures créées.

### Phase 10 : Optimisation & Finalisation MVP
- [ ] Gestion des messages flash (succès/erreurs).
- [ ] Nettoyage des requêtes SQL (Eager loading).
- [ ] Documentation finale de l'installation.

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