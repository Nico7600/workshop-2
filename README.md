<!--
    MyTicket - README.md
    Affichage uniquement en Français
-->

<p align="center">
  <img src="uploads/logo.png" alt="MyTicket Logo" width="120" style="border-radius: 16px; box-shadow: 0 2px 12px #38bdf880;">
</p>

<h1 align="center" style="color:#38bdf8; font-size:2.5rem;">🎟️ MyTicket</h1>

<p align="center" style="font-size:1.2rem;">
  <b>Plateforme de gestion de tickets de support en ligne</b>
</p>

<p align="center">
  <!-- Badges -->
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.1+-777bb4?logo=php&logoColor=white&style=flat-square">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-5.7+-4479A1?logo=mysql&logoColor=white&style=flat-square">
  <img alt="TailwindCSS" src="https://img.shields.io/badge/Tailwind_CSS-2.2+-38bdf8?logo=tailwindcss&logoColor=white&style=flat-square">
  <img alt="License" src="https://img.shields.io/badge/license-MIT-green?style=flat-square">
  <img alt="Lang" src="https://img.shields.io/badge/lang-FR-blue?style=flat-square">
  <img alt="Contributions" src="https://img.shields.io/badge/contributions-bienvenues-brightgreen?style=flat-square">
</p>

---

## 🇫🇷 Présentation

**MyTicket** est une application web moderne permettant la gestion de tickets de support pour les utilisateurs et les administrateurs.  
Elle propose une interface intuitive, un système de rôles avancé, la gestion des permissions, l’archivage des tickets, et bien plus.

> **Démo rapide**  
> [![Voir la démo](https://img.shields.io/badge/Demo-MyTicket-blue?logo=eye&style=for-the-badge)](https://github.com/Nico7600/workshop-2)

---

### ✨ Fonctionnalités principales

- ✅ Création, consultation et archivage de tickets
- ✅ Système de rôles (Utilisateur, Guide, Modérateur, Développeur, Administrateur)
- ✅ Gestion des permissions par rôle
- ✅ Interface d’administration complète (utilisateurs, rôles, permissions, bans, patchnotes)
- ✅ Notifications par email lors de l’archivage d’un ticket
- ✅ Multilingue : Français 🇫🇷, Anglais 🇬🇧, Néerlandais 🇳🇱
- ✅ Interface responsive et moderne (Tailwind CSS)
- ✅ Authentification sécurisée, gestion de profil avec photo
- ✅ Statistiques utilisateur et historique des tickets
- ✅ Système de votes sur les patchnotes
- ✅ Filtres et recherche de tickets

---

### 🏅 Badges de rôles

| ![Utilisateur](images_statut/utilisateur.png) Utilisateur | ![Guide](images_statut/guide.png) Guide | ![Modérateur](images_statut/modo.png) Modérateur | ![Développeur](images_statut/dev.png) Développeur | ![Admin](images_statut/admin.png) Admin |
|:---:|:---:|:---:|:---:|:---:|
| Utilisateur | Guide | Modérateur | Développeur | Administrateur |

---

## 🚀 Installation rapide

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/Nico7600/workshop-2.git
   cd workshop-2
   ```

2. **Configurer la base de données**
   - Importez le fichier `database.sql` dans votre serveur MySQL.
   - Configurez le fichier `.env` avec vos identifiants MySQL (voir `.env.example`).

3. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

4. **Lancer le serveur local**
   ```bash
   php -S localhost:8000
   ```
   Accédez à [http://localhost:8000](http://localhost:8000)

---

## 🛠️ Technologies utilisées

- **PHP 8.1+**
- **MySQL 5.7+**
- **Tailwind CSS**
- **Composer**
- **Dotenv**
- **FontAwesome**
- **HTML5 / CSS3**

---

## 📂 Structure du projet

```text
workshop-2-main/
├── about_us.php
├── admin.php
├── archive_tickets.php
├── assets/
├── db_connection.php
├── footer.php
├── header.php
├── index.php
├── login.php
├── logout.php
├── patchnote.php
├── privacy_policy.php
├── profile.php
├── register.php
├── support.php
├── terms_of_use.php
├── uploads/
├── vendor/
└── ...
```

---

## 👤 Auteurs & Contributeurs

- **Nicolas** – [Nico7600](https://github.com/Nico7600)
- **Shanni** – [Shanni](https://github.com/shannimln)
- **Contributions** : Les contributions sont les bienvenues !  
  Merci de soumettre vos issues et pull requests.

---

## 📄 Licence

Ce projet est distribué sous la licence MIT.

```text
Licence MIT

Copyright (c) 2024-2025

La permission est accordée, gratuitement, à toute personne obtenant une copie
de ce logiciel et des fichiers de documentation associés (le "Logiciel"), de
traiter le Logiciel sans restriction, y compris sans limitation les droits
d'utiliser, copier, modifier, fusionner, publier, distribuer, sous-licencier,
et/ou vendre des copies du Logiciel, et de permettre aux personnes à qui le
Logiciel est fourni de le faire, sous réserve des conditions suivantes :

La notice de copyright ci-dessus et la présente notice d'autorisation doivent
être incluses dans toutes copies ou parties substantielles du Logiciel.

LE LOGICIEL EST FOURNI "EN L'ÉTAT", SANS GARANTIE D'AUCUNE SORTE, EXPLICITE
OU IMPLICITE, Y COMPRIS MAIS SANS S'Y LIMITER AUX GARANTIES DE QUALITÉ
MARCHANDE, D'ADÉQUATION À UN USAGE PARTICULIER ET D'ABSENCE DE CONTREFAÇON.
EN AUCUN CAS LES AUTEURS OU LES TITULAIRES DU DROIT D'AUTEUR NE POURRONT ÊTRE
TENUS RESPONSABLES DE TOUTE RÉCLAMATION, DOMMAGE OU AUTRE RESPONSABILITÉ,
QUE CE SOIT DANS UNE ACTION CONTRACTUELLE, DÉLICTUELLE OU AUTRE, PROVENANT DE,
HORS OU EN RELATION AVEC LE LOGICIEL OU L'UTILISATION OU D'AUTRES TRAITEMENTS
DANS LE LOGICIEL.
```

---

## 💬 Contact

Pour toute question, suggestion ou bug, ouvrez une [issue GitHub](https://github.com/Nico7600/workshop-2/issues)  
ou contactez-nous à : [depretsnico@gmail.be](mailto:depretsnico@gmail.com)

---

<p align="center">
  <b>MyTicket - 2024-2025</b><br>
  <a href="https://github.com/Nico7600/workshop-2">Voir sur GitHub</a>
</p>
