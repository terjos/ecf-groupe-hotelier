# ECF Groupe hôtelier

## Installation en local:

Les pré-requis techniques:

-   php 8.0.0
-   composer
-   node
-   npm
-   [Symfony CLI](https://symfony.com/download)
-   [docker](https://docs.docker.com/compose/install/)

Si vous n'installez pas docker il vous faudra configurer le ficher .env pour modifier les variables DATABASE_URL et MAILER_DSN puis créer la basse de données correspondante.

Cloner le projet:

```bash
git clone https://github.com/terjos/ecf-groupe-hotelier.git
```

Aller dans le répertoire du projet:

```bash
cd ecf-groupe-hotelier
```

Installer les dépendances:

```bash
composer install
npm install
```

Controler vos dépendances:

```bash
symfony check:requirements
```

Builder les assets:

```bash
npm run build
```

Lancer docker:

```bash
docker-compose up --build
```

Configurer la base de données et charger les fixtures:

```bash
symfony console doctrine:migration:migrate
symfony console doctrine:fixtures:load
```

lancer le serveur de développement:

```bash
symfony serve
```

Ouvrez votre navigateur et accédez à http://localhost:8000/.

## Déploiement du site avec rsync et ssh:

Sur votre serveur configurer les variables d'environnement:

`APP_ENV=prod`

`APP_SECRET`

`MAILER_DSN`

`DATABASE_URL`

Modifier le nom du server (node177-eu.n0c.com), le nom d'utilisateur (puqccmdc) et le dossier racine (public_html) dans le ficher deploy et updateDB à la racine du projet.

lancer les commandes:

```bash
./deploy
./updateDB
```

**⚠ Ne lancer la commande updateDB qu'à la première mise en production (elle reset la DB)**

## Authors

-   [terjos](https://github.com/terjos)
