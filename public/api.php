<?php
/*
PROJET TODOS LIST
version API

//Pour une structure de forme micro-service Front + API, les views sont dorénavent entre les mains du HTML-CSS-JS
// Javascript s'occupe d'effectuer un Fetch() vers la route du service voulu, en incluant les bons paramètres (method, entête, data, ...) et formate et génère l'affichage après réception des données via le JSON retourné

Ce fichier api.php fait office de routeur pour les endpoints de l'api. En soit, ce n'est pas différent de ce qui a été fait avant. Il faut juste adapter les endpoints et les Controller

Liste des routes :
- Enregistrer 1 utilisateur (POST : /api/register)
- Connecter 1 utilisateur (POST : /api/login) -> pas de GET pour éviter d'envoyer les informations sensibles dans l'url. De plus, la connexion crée une SESSION, ce qui modifie l'état du server
- Lire tous les utilisateurs (GET : /api/users)
- Afficher le compte d'un utilisateur (GET : /api/me)
- Se Déconnecter (POST : /api/logout) -> pas de GET, la déconnexion, en détruisant la SESSION, modifie l'état du server
*/
//Démarrage de la session
session_start();

//Importer les ressources
require_once '../env.php';
require_once '../utils/utils.php';
include '../controller/deco.php';
include '../controller/Controller.php';
include '../model/Model.php';
include '../model/ModelUser.php';
include '../controller/ControllerUser.php';

// 1. Récupérer l'url de la requête
$url = $_SERVER["REQUEST_URI"];

// 2. Récupérer le path de la requête
$path = parse_url($url)["path"];

// 3. Vérifier le path et Lancer le controller selon l'url demandé
switch($path){
    // Route : récupérer les informations des utilisateurs
    case BASE_URL."api/users" :
        //Création du Model à exploiter. J'utilise la fonction connect() provenant du fichier utils.php pour générer l'objet PDO nécessaire
        $modelUser = new ModelUser(connect());

        //Créeation du controller
        $controller = new ControllerUser();
        $controller->addModel('modelUser',$modelUser);

        //Envoie de la liste des users
        $controller->list(["GET"]);

        break;
        
    default :
        http_response_code(404);
        echo(json_encode(["code"=>404, "message"=>"Error 404 Not Found !"]));
}