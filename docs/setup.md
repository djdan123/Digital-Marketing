# Installation et configuration du backend TruckAll

## Objectif

Ce document explique comment configurer le backend Laravel pour utiliser MySQL, la gestion des queues, le cache et le stockage local.

## Prérequis

- PHP 8.4+
- Composer
- MySQL
- Redis (optionnel pour le cache et les queues en production)
- Git

## Configuration de l'environnement

1. Copier l'exemple de configuration :

```bash
cp .env.example .env
```

2. Ouvrir `.env` et remplacer les valeurs suivantes pour MySQL :

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=truckall
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

3. S'assurer que l'application connaît l'URL du frontend :

```dotenv
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000
```

## Installation des dépendances

```bash
composer install
```

## Clé d'application

```bash
php artisan key:generate
```

## Création de la base de données MySQL

Créer la base MySQL `truckall` si elle n'existe pas :

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS truckall CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## Tables supplémentaires pour le système

Pour stocker les sessions, les caches et les jobs dans la base MySQL, utiliser :

```bash
php artisan session:table
php artisan cache:table
php artisan queue:table
php artisan migrate
```

## Stockage des fichiers

Créer le lien symbolique entre `storage` et `public` :

```bash
php artisan storage:link
```

## Lancer les migrations

```bash
php artisan migrate
```

## Vérifier l'état des migrations

```bash
php artisan migrate:status
```

## Lancer le worker de queue

```bash
php artisan queue:work --tries=3
```

### Note

Si le worker ne traite aucun job, cela peut simplement signifier qu'il n'y a pas de jobs en attente. Pour vérifier, ajoutez un job dans la queue ou regardez la table `jobs`.

## Notes de déploiement

- En production, `APP_DEBUG` doit être `false`.
- Utiliser `QUEUE_CONNECTION=redis` et `CACHE_STORE=redis` pour de meilleures performances en production.
- Configurer `SESSION_DRIVER=database` ou `redis` selon l'architecture souhaitée.
