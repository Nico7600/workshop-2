<!--
    MyTicket - README.md
    Affichage uniquement en Français
-->

<p align="center">
  <img src="uploads/logo.png" alt="MyTicket Logo" width="100" />
</p>

<h1 align="center">🎟️ MyTicket</h1>

<p align="center">
  <b>Plateforme de gestion de tickets de support en ligne</b>
</p>

<p align="center">
  <!-- Badges -->
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.1+-777bb4?logo=php&logoColor=white&style=flat-square">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-5.7+-4479A1?logo=mysql&logoColor=white&style=flat-square">
  <img alt="TailwindCSS" src="https://img.shields.io/badge/Tailwind_CSS-2.2+-38bdf8?logo=tailwindcss&logoColor=white&style=flat-square">
  <img alt="License" src="https://img.shields.io/badge/license-MIT-green?style=flat-square">
  <img alt="Lang" src="https://img.shields.io/badge/lang-FR-blue?style=flat-square">
</p>

---

## 🇫🇷 Présentation

**MyTicket** est une application web moderne permettant la gestion de tickets de support pour les utilisateurs et les administrateurs. Elle propose une interface intuitive, un système de rôles avancé, la gestion des permissions, l’archivage des tickets, et bien plus.

### Fonctionnalités principales

- Création, consultation et archivage de tickets
- Système de rôles (Utilisateur, Guide, Modérateur, Développeur, Administrateur)
- Gestion des permissions par rôle
- Interface d’administration complète (utilisateurs, rôles, permissions, bans, patchnotes)
- Notifications par email lors de l’archivage d’un ticket
- Multilingue : Français 🇫🇷, Anglais 🇬🇧, Néerlandais 🇳🇱
- Interface responsive et moderne (Tailwind CSS)
- Authentification sécurisée, gestion de profil avec photo

### Badges de rôles

| ![Utilisateur](images_statut/utilisateur.png) Utilisateur | ![Guide](images_statut/guide.png) Guide | ![Modérateur](images_statut/modo.png) Modérateur | ![Développeur](images_statut/dev.png) Développeur | ![Admin](images_statut/admin.png) Admin |
|:---:|:---:|:---:|:---:|:---:|
| Utilisateur | Guide | Modérateur | Développeur | Administrateur |

---

## 🚀 Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/Nico7600/workshop-2.git
   cd workshop-2-main
   ```

2. **Configurer la base de données**
   - Importez le fichier `database.sql` dans votre serveur MySQL.
   - Configurez le fichier `.env` avec vos identifiants MySQL.

3. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

4. **Lancer le serveur**
   ```bash
   php -S localhost:8000
   ```
   Accédez à [http://localhost:8000](http://localhost:8000)

---

## 📂 Structure

```
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

## 📄 Licence

MIT

---

<p align="center">
  <b>MyTicket - 2024-2025</b>
</p>
