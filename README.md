# TodosList — DEV26

Projet fil rouge de la formation Développeur BAC+2 : une application de gestion de tâches (Todo List) en PHP.

Ce dépôt contient les **démo-corrections** des différentes étapes du TP. Chaque étape correspond à une branche.

## Les branches

| Branche | Étape | Contenu |
|---|---|---|
| `main` | PHP procédural | Inscription d'un utilisateur, vérification de l'email déjà utilisé |
| `connexion` | PHP procédural | Connexion, déconnexion, include du header et du footer, affichage dynamique des formulaires et du menu |
| `mvc` | MVC POO | Passage en architecture MVC orientée objet |
| `mvc-heritage` | MVC POO avancé | Classes génériques `Model`, `View`, `Controller` et héritage |
| `routeur` | MVC POO + routeur | Routeur unique par réécriture d'URL (`.htaccess`), plusieurs pages |

Pour récupérer une branche précise :

```bash
git checkout routeur
```

## Fonctionnalités (branche `routeur`)

- Page d'accueil avec formulaire d'inscription, formulaire de connexion et liste des utilisateurs
- Page « Mon Compte » affichant les informations de l'utilisateur connecté
- Déconnexion
- Affichage dynamique : les formulaires disparaissent et le menu change selon que l'utilisateur est connecté ou non

## Prérequis

- **PHP 8.0** ou supérieur, avec les extensions `pdo_mysql` et `mbstring`
- **MySQL 8.0.13+** ou **MariaDB 10.2+**
- **Apache** avec :
  - le module `mod_rewrite` activé
  - `AllowOverride All` sur le dossier du projet (sinon les `.htaccess` sont ignorés)

Sous WAMP / XAMPP / Laragon, `mod_rewrite` s'active depuis le menu Apache de l'outil.

Sous Debian / Ubuntu :

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Et dans `/etc/apache2/apache2.conf` (ou dans le virtual host) :

```apache
<Directory /var/www/>
    AllowOverride All
</Directory>
```

## Installation

**1. Cloner le dépôt dans un dossier nommé `TodosList`** à la racine de votre serveur web (`www/`, `htdocs/`, `/var/www/html/`…) :

```bash
git clone -b routeur https://github.com/Kellyan01/TodosList-DEV26.git TodosList
```

> ⚠️ Sans le dernier argument, Git crée un dossier `TodosList-DEV26` et toutes les pages renverront une erreur 404. Pour utiliser un autre nom de dossier, voir [Changer le nom du dossier](#changer-le-nom-du-dossier).

**2. Créer la base de données** en important le script SQL, via phpMyAdmin (onglet *Importer*) ou en ligne de commande :

```bash
mysql -u root -p < sql/TodoListBDD.sql
```

Le script crée la base `todos`, ses tables, les rôles (`Administrateur`, `Utilisateur`) et quelques catégories.

**3. Créer le fichier de configuration** en copiant le fichier d'exemple :

```bash
cp env-example.php env.php
```

**4. Renseigner `env.php`** avec vos propres identifiants :

```php
$_ENV['dbhost'] = 'localhost';
$_ENV['dbname'] = 'todos';
$_ENV['dblogin'] = 'root';
$_ENV['dbpassword'] = '';

const BASE_URL = '/TodosList/';
```

> `env.php` contient des informations sensibles : il est listé dans le `.gitignore` et ne doit **jamais** être versionné.

**5. Ouvrir le projet** dans le navigateur : [http://localhost/TodosList/](http://localhost/TodosList/)

Créez ensuite un compte avec le formulaire d'inscription (mot de passe de 12 caractères minimum). Pour lui donner le rôle administrateur :

```sql
UPDATE users SET id_role = 1 WHERE email = 'votre@email.fr';
```

## Changer le nom du dossier

Si le projet n'est pas dans un dossier `TodosList`, trois endroits sont à mettre à jour avec le même chemin :

| Fichier | Ligne à modifier |
|---|---|
| `env.php` | `const BASE_URL = '/MonDossier/';` |
| `.htaccess` (racine) | `RewriteBase /MonDossier/` |
| `public/.htaccess` | `RewriteBase /MonDossier/public/` |

Pour un projet rangé dans un sous-dossier (ex : `www/formation/TodosList/`), le chemin devient `/formation/TodosList/`.

## Les routes

| URL | Page | Controller |
|---|---|---|
| `/TodosList/` | Accueil : inscription, connexion, liste des utilisateurs | `ControllerAccueil` |
| `/TodosList/moncompte` | Informations du compte connecté | `ControllerCompte` |
| `/TodosList/deconnexion` | Déconnexion puis redirection vers l'accueil | `deconnect()` |
| Toute autre URL | Erreur 404 | — |

## Fonctionnement du routeur

1. Le `.htaccess` à la racine redirige **toutes** les requêtes vers le dossier `public/`. Les dossiers `model/`, `view/`, `controller/` et le fichier `env.php` ne sont donc jamais accessibles directement depuis le navigateur.
2. Le `.htaccess` de `public/` redirige toute requête qui ne correspond ni à un fichier ni à un dossier existant vers `public/index.php`.
3. `public/index.php` joue le rôle de **routeur** : il lit l'URL demandée, instancie le Model, les Views et le Controller correspondants, puis lance le rendu de la page.

## Structure du projet

```
TodosList/
├── .htaccess              # Redirige toutes les requêtes vers public/
├── env-example.php        # Modèle du fichier de configuration
├── env.php                # Configuration locale (non versionnée)
├── controller/
│   ├── Controller.php         # Controller générique (ne pas instancier)
│   ├── ControllerAccueil.php
│   ├── ControllerCompte.php
│   └── deco.php               # Fonction de déconnexion
├── model/
│   ├── Model.php              # Model générique (ne pas instancier)
│   └── ModelUser.php
├── view/
│   ├── View.php               # View générique (ne pas instancier)
│   ├── ViewHeader.php
│   ├── ViewFooter.php
│   ├── ViewAccueil.php
│   └── ViewCompte.php
├── public/
│   ├── .htaccess          # Redirige vers index.php
│   └── index.php          # Point d'entrée unique : le routeur
└── sql/
    └── TodoListBDD.sql    # Script de création de la base
```

## Base de données

| Table | Rôle |
|---|---|
| `users` | Les utilisateurs (mot de passe hashé avec `password_hash()`) |
| `roles` | Les rôles : Administrateur, Utilisateur |
| `todos` | Les tâches, rattachées à un utilisateur |
| `categories` | Les catégories de tâches |
| `todos_categories` | Table d'association : une tâche peut avoir plusieurs catégories |

## Dépannage

| Symptôme | Cause probable |
|---|---|
| Erreur 404 sur toutes les pages | Le nom du dossier ne correspond pas à `BASE_URL` et aux `RewriteBase` |
| Erreur 500 Internal Server Error | `mod_rewrite` n'est pas activé |
| L'accueil marche, mais `/moncompte` renvoie une 404 Apache | `AllowOverride` n'est pas à `All` : les `.htaccess` sont ignorés |
| `Failed opening required '../env.php'` ou `Undefined constant "BASE_URL"` | Le fichier `env.php` n'existe pas ou est incomplet |
| `SQLSTATE[HY000] [1045] Access denied` | Identifiants de connexion incorrects dans `env.php` |
| `SQLSTATE[HY000] [1049] Unknown database` | La base n'a pas été importée, ou `dbname` est incorrect |

## Notions abordées

- Superglobales `$_GET`, `$_POST`, `$_ENV`, `$_SESSION`, `$_SERVER`
- PDO et requêtes préparées
- POO : classes, héritage, composition, polymorphisme
- Architecture MVC
- Routeur par réécriture d'URL avec `.htaccess`
- Sécurité : `isset()`, `empty()`, `trim()`, `filter_var()`, `htmlentities()`, `password_hash()`, `password_verify()`