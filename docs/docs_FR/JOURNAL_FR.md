# 📔 Journal de Bord / Development Log — Freelance Flow

## 🇫🇷 Français

> **État actuel :** Phase 1 Terminée
> **Prochaine étape :** Phase 2 Entité & Gestion CRUD

---

## Jour 1 : 16 Janvier 2026 — Authentification & UI (Phase 1)

### Travaux effectués
- [x] **Système d'Auth :** Génération de l'entité `User`, du `LoginFormAuthenticator` et du `SecurityController`.
- [x] **Base de données :** Configuration de MariaDB 10.4.32 et exécution des migrations.
- [x] **Sécurité :** Protection du `DashboardController` via l'attribut `#[IsGranted('ROLE_USER')]`.
- [x] **Interface :** stylisation avec Tailwind CSS.
- [x] **Flux :** Unification de la logique de redirection post-connexion vers le dashboard.
- [x] **Intégration Design :** Implémentation des variables sémantiques Tailwind v4 (`primary`, `app-bg`) sur les formulaires de connexion et d'inscription pour unifier le Design System.

### Phase 2 : Modélisation des Données & Logique Métier
- [x] **Conception :** Création et documentation du schéma de données (MCD, MLD, ERD) dans `docs/database/`.

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

