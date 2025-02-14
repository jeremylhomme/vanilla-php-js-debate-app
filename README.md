# Express Debate

Une plateforme de débats éphémères où les utilisateurs peuvent proposer des sujets, voter et commenter anonymement. Les débats expirent automatiquement après 48 heures.

## Fonctionnalités

- Liste des débats en cours
- Soumission de nouveaux débats
- Système de vote
- Commentaires anonymes
- Suppression automatique des débats après 48h
- Interface responsive

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache, Nginx, etc.)
- Extension PDO PHP activée

## Installation

1. Clonez le dépôt dans votre répertoire web :

```bash
git clone [url-du-repo] express-debate
```

2. Importez la base de données :

```bash
mysql -u root -p < database.sql
```

3. Configurez la connexion à la base de données dans `db.php` :

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');
define('DB_NAME', 'express_debate');
```

4. Assurez-vous que les événements MySQL sont activés :

```sql
SET GLOBAL event_scheduler = ON;
```

## Structure du projet

- `index.php` : Page d'accueil listant les débats
- `debate.php` : Page d'un débat individuel
- `submit.php` : Formulaire de soumission de débat
- `vote.php` : Gestion des votes (API)
- `comment.php` : Gestion des commentaires (API)
- `db.php` : Configuration de la base de données
- `style.css` : Styles CSS
- `script.js` : JavaScript pour les interactions AJAX
- `database.sql` : Script d'initialisation de la base de données

## Utilisation

1. Accédez à la page d'accueil pour voir les débats en cours
2. Cliquez sur "Proposer un débat" pour créer un nouveau sujet
3. Votez pour les débats qui vous intéressent
4. Cliquez sur un débat pour voir les détails et les commentaires
5. Ajoutez des commentaires de manière anonyme

## Maintenance

Les débats sont automatiquement supprimés après 48 heures grâce à un événement MySQL programmé. Aucune maintenance manuelle n'est nécessaire.

## Sécurité

- Toutes les entrées utilisateur sont filtrées et échappées
- Protection contre les injections SQL via PDO
- Pas de système d'authentification requis (anonyme)

## Licence

MIT
