# 📔 Journal de Bord / Development Log — Freelance Flow [Version Français]

## 📅 Jour 6 : 23/01/2026 - Moteur de Facturation - Backend & Logique (Phase 5)
### Réalisations :
- [x] **Security Voters :** Ajout de ClientVoter pour une isolation stricte des données multi‑tenant.
- [x] **Access Control :** Finalisation de security.yaml pour sécuriser toutes les routes protégées.
- [x] **Refactor Entité :** Relation directe User → Invoice pour une gestion claire de la propriété.
- [x] **Sujet de Facture :** Ajout de project_title pour organiser les lignes par projet.
- [x] **Invoice CRUD :** Création complète des pages de création, édition, affichage et suppression.
- [x] **InvoiceVoter :** Mise à jour pour utiliser la relation directe User.
- [x] **Numérotation :** Implémentation de InvoiceNumberGenerator (ex. FF‑2026‑001).


## 📅 Jour 5 : 20/01/2026 - Interface Globale & Sécurité (Phase 4)
### Technical Fixes:
- [x] **Conflit d’utilitaires Tailwind v4 :** Correction d’un problème où hidden et whitespace-nowrap ne fonctionnaient pas correctement ensemble.
- [x] **Débogage Turbo Frame :** Résolution d’un blocage du bouton « Save » causé par une interception Turbo ; ajustement des cibles de formulaires pour garantir une mise à jour correcte de l’état.
- [x] **Retour de validation des formulaires :** Correction de l’absence d’affichage des erreurs dans les templates Twig ; les messages de validation Symfony apparaissent désormais de manière cohérente.
- [x] **Fiabilité des messages flash :** Correction d’un bug empêchant l’affichage des messages de succès/erreur à cause d’un markup non compatible avec Turbo.
- [x] **Bug d’état du menu déroulant :** Résolution d’un problème où le menu utilisateur restait ouvert après navigation sous Turbo.
- [x] **Overlay du sidebar mobile :** Correction d’un conflit de z-index qui faisait apparaître l’overlay derrière le contenu sur les petits écrans.

### Réalisations :
- [x] **Navigation Dynamique :** Configuration de la logique Twig pour détecter les routes actives (app.request.get('_route')) et appliquer sur (Dashboard, Clients).
- [x] **Architecture Pro :** Séparation du code en base.html.twig (structure globale) et index.html.twig (contenu) pour garantir un héritage de template évolutif.
- [x] **startStimulusApp** Migration de la logique d'interface vers un contrôleur Stimulus pour une navigation fluide et compatible avec Turbo.
- [x] **Architecture Redesign:** Revamped base.html.twig to introduce a premium sidebar navigation system. Added modern Glassmorphism styling powered by Tailwind v4.


## 📅 Jour 4 : 19/01/2026 - Interface Globale & Sécurité (Phase 4)
### Réalisations :
- [x] **Gestion de Projet :** Finalisation de la feuille de route MVP (Phases 4 à 10) et mise à jour de la documentation.
- [x] **Refonte du Layout Maître :** Transformation de base.html.twig en une structure de tableau de bord robuste avec une barre latérale (sidebar) fixe et responsive.
- [x] **Système de Design "Glass" :** Implémentation d'effets de transparence et de flou (backdrop-blur) via les classes utilitaires de Tailwind v4 et des variables de thème.
- [x] **Logique Native JS :** Développement d'un script JavaScript Vanilla personnalisé pour gérer le basculement du menu burger, la visibilité dynamique du logo


## 📅 Jour 3 [18 Janvier 2026] - Profil, Paramètres et CRUD Client (Phase 3)
### Réalisations :
- [x] **Paramètres & Profil :** Création du `SettingsController` et implémentation de la mise à jour du profil utilisateur (Noms, Email).
- [x] **Sécurité Client :** Génération d'un CRUD Client sécurisé. Les données sont strictement filtrées par l'utilisateur connecté (`getUser`).
- [x] **Logique Métier :** Automatisation de l'assignation de l'utilisateur pour les nouveaux clients et protection des routes `show/edit/delete` via vérification de propriété.
- [x] **Infrastructure Tailwind v4 :**
- [x] Débogage et résolution du problème d'affichage via l'implémentation de la directive `@source` dans `app.css`.
- [x] Purge complète du système (`var/tailwind` et `asset-map`) pour corriger les problèmes de persistance du cache.
- [x] **Modernisation de l'UI :**
- [x] **Index Responsive :** Construction d'un tableau style "SaaS" masquant les colonnes secondaires sur mobile tout en gardant les "Actions" accessibles.
- [x] **Design des Formulaires :** Création d'une grille à deux colonnes pour les formulaires New/Edit avec les variables de thème Indigo/Slate.
- [x] **Vue Profil :** Implémentation d'une page "Show" avec en-tête de profil, initiales d'avatar générées et grilles de données structurées.


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

