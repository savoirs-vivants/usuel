## Aperçu du Projet

**Plateforme de Questionnaires et Passations Évaluatives**

Cette application Laravel gère des questionnaires comportementaux et évaluatifs :

- **Utilisateurs** : Inscription par invitation (token), gestion profil, admin backoffice.
- **Questionnaires** : Création/gestion questions, passation (prise du test), résultats, certificats PDF.
- **Backoffice** : CRUD utilisateurs/questions/passations, statistiques publiques/comportementales, exports Excel.
**Autres** : Emails invitation/reset PW, tracking réponses, **audio TTS (text-to-speech questions/choix via SpeechSynthesis API browser)**, **traduction Google API (stichoza/google-translate-php)**, multi-langues.

**Technos** : Laravel 12, Livewire v4, TailwindCSS + Flowbite, Vite, PHPSpreadsheet.

## Prérequis

- PHP ^8.2
- Composer
- Node.js / NPM
- MySQL/MariaDB 
- Serveur web

## Installation

1. **Cloner** le projet : https://git.unistra.fr/strebes/litteratie-savoirs-vivants.git
2. **Dépendances PHP** :
   ```
   composer install
   cp .env.example .env, 
   php artisan key:generate, 
   
   ```
3. **Configurer .env** :
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=usuel  # Créez la DB MySQL
   DB_USERNAME=root
   DB_PASSWORD= 

   APP_URL=http://127.0.0.1:8000
   MAIL_MAILER=smtp  # Config mail pour invitations
   ```
4. **Migrations & Seed** :
   ```
   php artisan migrate
   ```
5. **Assets** :
   ```
   npm install
   npm run build
   ```


## Lancement Local

**Lancement** :
```
php artisan serve
```

Accès :
- Welcome : http://127.0.0.1:8000

**Prod** :
```
npm run build
```

## Accès Admin

- Créez premier user admin via `php artisan tinker` :
  ```php
  User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('password'),'is_admin'=>true]);
  ```
