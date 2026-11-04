<?php
//Fichier d'exemple pour env.php
//Identifiant de connexion à la BDD
$_ENV['dbhost'] = 'exemple';
$_ENV['dbname'] = "exemple";
$_ENV['dblogin'] = 'exemple';
$_ENV['dbpassword'] = "exemple";

//Chemin du projet depuis la racine du serveur web (avec le / de début et de fin)
//Doit correspondre aux RewriteBase des deux .htaccess
const BASE_URL = "/TodosList/";