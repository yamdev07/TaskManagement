
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# ✅ TaskManagement - Laravel App

TaskManagement est une application web construite avec le framework **Laravel**.  
Elle permet aux utilisateurs de gérer efficacement leurs tâches : création, modification, suivi et suppression.

---

## 🚀 Fonctionnalités principales

- ✅ CRUD complet des tâches (Créer, Lire, Modifier, Supprimer)
- 🔍 Filtrage ou tri des tâches
- 📆 Gestion des dates et échéances
- 👥 Authentification des utilisateurs (Laravel Breeze/Fortify/Jetstream, selon ton choix)
- 🎨 Interface responsive avec Blade + Bootstrap/Tailwind *(selon ton projet)*

---

## 📥 Comment installer ce projet

### 1. Cloner le dépôt

```bash
git clone https://github.com/yamdev07/TaskManagement.git
cd TaskManagement

1. Cloner le dépôt
bash
Copier
Modifier
git clone https://github.com/yamdev07/TaskManagement.git
cd TaskManagement
2. Installer les dépendances PHP
bash
Copier
Modifier
composer install
3. Copier le fichier d’environnement
bash
Copier
Modifier
cp .env.example .env
4. Générer la clé d'application
bash
Copier
Modifier
php artisan key:generate
5. Configurer la base de données
Dans le fichier .env, modifie ces lignes selon ta configuration MySQL :

env
Copier
Modifier
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
🛑 Important : n'oublie pas de créer une base de données task_management dans ton SGBD (ex : phpMyAdmin ou MySQL Workbench).

6. Lancer les migrations (et les seeders si besoin)
bash
Copier
Modifier
php artisan migrate
# php artisan db:seed
7. Lancer le serveur local
bash
Copier
Modifier
php artisan serve
Puis ouvre ton navigateur sur :

👉 http://localhost:8000


