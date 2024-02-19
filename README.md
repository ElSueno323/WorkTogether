# Work Together
A task tracking app.

Test the app at https://worktogether.alwaysdata.net or at https://worka.azurewebsites.net/ !

# Installation

1. Clone the project.
2. Copy .env.example to .env, as well as its dusk and testing counterpart
3. Run `composer install`
4. Run `php artisan key:generate`
5. Copy the `APP_KEY` found in `.env` in `.env.dusk`and `.env.testing`
6. Run `php artisan migrate:fresh --seed`

## Optional

7. For SSO Authentication, fill the given client IDs, secrets etc. in `.env`

For security reasons, these credentials are not in `.env.example`. To obtain them,
fetch them directly from the Cloud Providers or ask Ayoub Moufidi to send them to 
you (not ideal either).

8. For profile pictures, run `php artisan storage:link`.

# Manual Deployment

1. Connect to the server using SSH
    * URL : `worktogether@ssh-worktogether.alwaysdata.net`
    * Password : Ask Ayoub
2. Get to the laravel folder : `cd www`
3. Update the code : `git pull`
4. Install the dependencies : `composer2 install --no-dev --optimize-autoloader` and `npm install`
5. Migrate the database : `php artisan migrate` NEVER FRESH/REFRESH !!!
6. Build the Vite thingies : `npm run build`
7. Cache everything :
    * `php artisan config:cache`
    * `php artisan route:cache`
    * `php artisan view:cache`

# Troubleshooting

## Database file at path ...\database.sqlite does not exist

Run `php artisan migrate --seed` instead.

# Changelog
- 16/10/2023 - List of task
- 23/10/2023 - Creation of groups

# Technology
## Front-end
- Bootstrap
## Back-end
- Laravel
- Blade
- PHP 8.0

# Sprints
1. 16/10/2023 -> 23/10/2023
2. 09/11/2023 -> 16/11/2023

# Members
- Sara WYSK
- Yassine FERRAJ 
- Gabriel ESPINOSA SANDOVAL
- Ayoub MOUFIDI
- Mohamed EL KAOUI
- Mossab DELBERGUE 
- Nicolas VERSCHEURE
- Sacha YOKO
- Soliman AZOZ
- Younes EL HARRAOUI
- Younes Oudahya
## Coach
- Frédéric SERVAIS

# Scénario
## FERRAJ Yassine
0. Télécharger une photo "admin" pour la démo
1. Connexion en tant qu'admin 
2. Menu Group -> Group list
3. Menu Group -> Create Group
3.1 Name -> Gestionnaires
3.1 Description ->  Les gestionnaires de la coopérative

4. Open Button "admin" -> Profile
5. Expliquer comment changer de mot de passe et inciter à le faire par mesure de sécurité (mot de passe par défaut connu de tous via le seeder)
6. Changer la photo de profil
7. Changer le Name -> Administrateur
8. Expliquer comment changer l'émail
9. Open Button "admin" -> Log Out
10. Login -> John smith
11. Menu Group -> Group list
12. Rejoindre un groupe
13. Open Button "John smith" -> Log Out
14. Login admin
15. Menu Group -> Group participants
16. Accepter John smith
