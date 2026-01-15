# 📔 Journal de Bord / Development Log — FreelanceFlow

---

## 🇫🇷 Français
### **Jour 0 : 15 Janvier 2026 — Initialisation & Cadrage**

#### ✅ Travaux effectués
- [x] **Initialisation technique :** Création du projet avec Symfony 7.4 (Pack `webapp`).
- [x] **Setup Frontend :** Installation de **Symfony AssetMapper** et du **Tailwind Bundle**.
- [x] **Documentation :** Rédaction du cahier des charges bilingue (FR/EN) et des spécifications techniques.
- [x] **Planification :** Définition d'une roadmap stratégique en **10 phases** pour le MVP.
- [x] **Versionnage :** Configuration du dépôt Git et premier push sur GitHub.

#### 🧠 Décisions Techniques
* **Architecture No-Node :** Choix de `symfony/asset-mapper` et `symfonycasts/tailwind-bundle` pour éliminer la dépendance à Node.js/npm. Cela simplifie le déploiement et améliore les performances de build.
* **Sécurité Native :** Décision d'implémenter les **Voters** dès le début pour garantir un cloisonnement strict des données entre les freelances.

#### État actuel
* **Phase 0 :** Terminée (Cadrage & Environnement).
* **Prochaine étape :** Phase 1 — Création de l'entité `User` et du système d'authentification.