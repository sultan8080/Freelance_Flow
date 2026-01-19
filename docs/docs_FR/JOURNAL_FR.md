# 📔 Journal de Bord / Development Log — Freelance Flow [Version Français]


## 📅 Jour 4 : 19/01/2026 - Interface Globale & Sécurité (Phase 4)
- [x] **Gestion de Projet :** Finalisation de la feuille de route MVP (Phases 4 à 10) et mise à jour de la documentation.

## 📅 Jour 3 [18 Janvier 2026] - Profil, Paramètres et CRUD Client (Phase 3)

### Réalisations :
- **Paramètres & Profil :** Création du `SettingsController` et implémentation de la mise à jour du profil utilisateur (Noms, Email).
- **Sécurité Client :** Génération d'un CRUD Client sécurisé. Les données sont strictement filtrées par l'utilisateur connecté (`getUser`).
- **Logique Métier :** Automatisation de l'assignation de l'utilisateur pour les nouveaux clients et protection des routes `show/edit/delete` via vérification de propriété.
- **Infrastructure Tailwind v4 :**
    - Débogage et résolution du problème d'affichage via l'implémentation de la directive `@source` dans `app.css`.
    - Purge complète du système (`var/tailwind` et `asset-map`) pour corriger les problèmes de persistance du cache.
- **Modernisation de l'UI :**
    - **Index Responsive :** Construction d'un tableau style "SaaS" masquant les colonnes secondaires sur mobile tout en gardant les "Actions" accessibles.
    - **Design des Formulaires :** Création d'une grille à deux colonnes pour les formulaires New/Edit avec les variables de thème Indigo/Slate.
    - **Vue Profil :** Implémentation d'une page "Show" avec en-tête de profil, initiales d'avatar générées et grilles de données structurées.

## 📅 Jour 2 : 17/01/2026 - Finalisation de la Conception et Initialisation Technique (Phase 2)

### Accomplissements :
- [x] **Architecture Documentaire :** Réorganisation complète dans `/docs/docs_FR`, `/docs/docs_EN`, et `/docs/database` pour un dépôt professionnel.
- [x] **README Principal :** Mise à jour avec une présentation du projet et intégration visuelle du diagramme MCD (SVG).
- [x] **Entité User :** Enrichissement avec les champs légaux (SIRET, TVA, adresse) et identité (Prénom, Nom).
- [x] **Automatisation (Traits) :** Création et intégration du `TimestampableTrait` pour gérer automatiquement `createdAt` et `updatedAt`.
- [x] **Qualité Technique :** Nettoyage des migrations obsolètes pour créer une "Master Migration" propre et activation des `HasLifecycleCallbacks`.
- [x] **Base de Données :** Réinitialisation complète et synchronisation réussie du schéma relationnel.
- [x] **Logique Invoice :** Mise à jour du constructeur pour initialiser l'état 'draft' et la `dueDate` à J+30.
- [x] **Automatisation :** Ajout d'un déclencheur dans `setStatus` pour remplir `paidAt` automatiquement lors du passage à l'état payé.


## 📅 Jour 1 : 16 Janvier 2026 — Authentification & UI (Phase 1)

### Travaux effectués
- [x] **Système d'Auth :** Génération de l'entité `User`, du `LoginFormAuthenticator` et du `SecurityController`.
- [x] **Base de données :** Configuration de MariaDB 10.4.32 et exécution des migrations.
- [x] **Sécurité :** Protection du `DashboardController` via l'attribut `#[IsGranted('ROLE_USER')]`.
- [x] **Interface :** stylisation avec Tailwind CSS.
- [x] **Flux :** Unification de la logique de redirection post-connexion vers le dashboard.
- [x] **Intégration Design :** Implémentation des variables sémantiques Tailwind v4 (`primary`, `app-bg`) sur les formulaires de connexion et d'inscription pour unifier le Design System.


### Décisions Techniques
* **Sécurité par Attributs :** Choix de `#[IsGranted]` pour un contrôle d'accès granulaire et lisible directement dans le code.
* **UX :** Centralisation des redirections pour offrir un parcours utilisateur fluide après l'inscription ou la connexion.

---

### **Jour 0 : 15 Janvier 2026 — Initialisation & Cadrage**

#### État actuel
* **Phase 0 :** Terminée (Cadrage & Environnement).
* **Prochaine étape :** Phase 1 — Création de l'entité `User` et du système d'authentification.

#### Travaux effectués
- [x] **Initialisation technique :** Création du projet avec Symfony 7.4 (Pack `webapp`).
- [x] **Setup Frontend :** Installation de **Symfony AssetMapper** et du **Tailwind Bundle**.
- [x] **Documentation :** Rédaction du cahier des charges bilingue (FR/EN) et des spécifications techniques.
- [x] **Planification :** Définition d'une roadmap stratégique en **10 phases** pour le MVP.
- [x] **Versionnage :** Configuration du dépôt Git et premier push sur GitHub.

#### Décisions Techniques
* **Architecture No-Node :** Choix de `symfony/asset-mapper` et `symfonycasts/tailwind-bundle` pour éliminer la dépendance à Node.js/npm. Cela simplifie le déploiement et améliore les performances de build.
* **Sécurité Native :** Décision d'implémenter les **Voters** dès le début pour garantir un cloisonnement strict des données entre les freelances.

