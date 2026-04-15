# Usuel — Plateforme de passations évaluatives

Application Laravel de gestion de questionnaires comportementaux et évaluatifs, avec suivi de passations, statistiques et exports.

## Fonctionnalités

- **Questionnaires** — création et gestion des questions, passation du test, résultats, génération de certificats PDF
- **Utilisateurs** — inscription par invitation (token), gestion de profil, backoffice admin
- **Statistiques** — tableaux de bord public et comportemental, exports Excel (PHPSpreadsheet)
- **Accessibilité** — audio text-to-speech via l'API Web SpeechSynthesis, traduction automatique via `stichoza/google-translate-php`, support multilingue

## Stack technique

| Couche | Technologie |
|---|---|
| Backend | Laravel 12, Livewire v4 |
| Frontend | TailwindCSS, Flowbite, Vite |
| Base de données | MySQL / MariaDB |
| Exports | PHPSpreadsheet |

## Prérequis

- PHP ^8.2
- Composer
- Node.js / NPM
- MySQL ou MariaDB
- Un serveur SMTP (pour les emails d'invitation)

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/savoirs-vivants/usuel.git
cd litteratie-savoirs-vivants
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Puis éditer `.env` avec vos valeurs :

```env
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=usuel
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
```

> Créez la base de données `usuel` dans MySQL avant de continuer.

### 4. Migrations

```bash
php artisan migrate
```

### 5. Compiler les assets

```bash
npm run build
```

## Lancement en local

```bash
php artisan serve
```

L'application est accessible sur [http://127.0.0.1:8000](http://127.0.0.1:8000).

> En développement, utilisez `npm run dev` à la place de `npm run build` pour le hot-reload.

## Créer le premier compte administrateur

```bash
php artisan tinker
```

```php
User::create([
    'name'     => 'Admin',
    'email'    => 'admin@example.com',
    'password' => bcrypt('password'),
    'role'     => 'admin',
]);
```

> Les autres utilisateurs sont créés via le système d'invitation depuis le backoffice.
