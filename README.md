# Balance Ton Flow - Plateforme de Vote en Direct

Une plateforme web dédiée aux phases finales du concours "Balance Ton Flow", permettant la diffusion en direct, le vote en temps réel, et des espaces personnalisés pour les finalistes.

## Fonctionnalités

- **Diffusion Live** : Intégration de streams en direct pour les phases demi-finales et finales
- **Vote en Temps Réel** : Système de vote sécurisé avec authentification SSO
- **Espaces Candidats** : Profils personnalisés pour chaque finaliste
- **Tableau de Bord Admin** : Gestion complète de la plateforme et statistiques
- **Authentification SSO** : Connexion via Google et Facebook

## Prérequis

- Docker et Docker Compose
- Git

## Installation

1. Clonez le dépôt :
   ```
   git clone https://github.com/votre-organisation/balancetonflow.git
   cd balancetonflow
   ```

2. Lancez les conteneurs Docker :
   ```
   docker compose up -d
   ```

   > **Note**: Utilisez `docker compose` (sans tiret) et non `docker-compose`

3. Installez les dépendances et configurez l'application :
   ```
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate
   docker compose exec app php artisan db:seed
   ```

4. Accédez à l'application :
   - Site web : http://localhost:8889
   - phpMyAdmin : http://localhost:8082 (utilisateur: user, mot de passe: password)

## Configuration des ports

Cette installation utilise les ports suivants :

- **8889** : Application web (Nginx)
- **8082** : phpMyAdmin
- **3307** : MySQL
- **6379** : Redis

## Développement

Pour exécuter des commandes Laravel :

```
docker compose exec app php artisan <commande>
```

Pour accéder au shell du conteneur :

```
docker compose exec app bash
```

## Accès aux tableaux de bord

### Tableau de bord administrateur

1. Accédez à l'application à l'adresse http://localhost:8889
2. Connectez-vous avec les identifiants administrateur :
   - Email : admin@balancetonflow.com
   - Mot de passe : admin123
3. Vous serez automatiquement redirigé vers le tableau de bord administrateur
4. Alternativement, vous pouvez accéder directement au tableau de bord via http://localhost:8889/admin

Le tableau de bord administrateur vous permet de :
- Gérer les candidats (ajouter, modifier, supprimer)
- Gérer les utilisateurs et leurs droits
- Configurer les livestreams et les votes
- Consulter les statistiques en temps réel
- Modérer les vidéos des candidats

### Tableau de bord candidat

1. Accédez à l'application à l'adresse http://localhost:8889
2. Connectez-vous avec les identifiants du candidat (créés par l'administrateur)
3. Vous serez automatiquement redirigé vers le tableau de bord candidat
4. Alternativement, vous pouvez accéder directement au tableau de bord via http://localhost:8889/dashboard

Le tableau de bord candidat permet aux finalistes de :
- Gérer leur profil (photo, description)
- Ajouter et gérer leurs vidéos
- Consulter leurs statistiques de votes
- Interagir avec leur audience

### Accès rapide via le script dashboard.php

Un script unifié a été créé pour remplacer Filament par un tableau de bord simple :

1. Accédez à http://localhost:8889/dashboard.php
2. Vous serez automatiquement redirigé vers le tableau de bord approprié selon votre rôle :
   - Administrateur → Tableau de bord admin
   - Candidat → Tableau de bord candidat
   - Utilisateur standard → Page d'accueil

## Authentification SSO

Pour configurer l'authentification avec Google et Facebook :

1. Créez des projets dans les consoles développeurs de Google et Facebook
2. Obtenez les identifiants OAuth (Client ID et Secret)
3. Configurez les URLs de redirection vers :
   - `http://localhost:8889/auth/google/callback`
   - `http://localhost:8889/auth/facebook/callback`
4. Ajoutez les identifiants dans le fichier `.env` :
   ```
   GOOGLE_CLIENT_ID=votre_client_id
   GOOGLE_CLIENT_SECRET=votre_client_secret
   FACEBOOK_CLIENT_ID=votre_client_id
   FACEBOOK_CLIENT_SECRET=votre_client_secret
   ```

## Structure du projet

- `app/` : Code source PHP de l'application
- `resources/views/` : Templates Blade pour le frontend
- `public/` : Fichiers publics (CSS, JS, images)
- `routes/` : Définition des routes de l'application
- `database/migrations/` : Migrations de base de données

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.
