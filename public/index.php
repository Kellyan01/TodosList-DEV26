<?php
/*
PROJET TODOS LIST
*/
//Démarrage de la session
session_start();

//Importer les ressources
require_once '../env.php';
require_once '../utils/utils.php';
include '../controller/deco.php';
include '../controller/Controller.php';
include '../model/Model.php';
include '../view/View.php';
include '../model/ModelUser.php';
include '../view/ViewHeader.php';
include '../view/ViewAccueil.php';
include '../view/ViewCompte.php';
include '../view/ViewFooter.php';
include '../controller/ControllerAccueil.php';
include '../controller/ControllerCompte.php';

// 1. Récupérer l'url de la requête
$url = $_SERVER["REQUEST_URI"];

// 2. Récupérer le path de la requête
$path = parse_url($url)["path"];

// 3. Vérifier le path et Lancer le controller selon l'url demandé
switch($path){
    case BASE_URL :
        //Création du Model à exploiter. J'utilise la fonction connect() provenant du fichier utils.php pour générer l'objet PDO nécessaire
        $modelUser = new ModelUser(connect());

        //Création des View
        $viewHeader = new ViewHeader();
        $viewFooter = new ViewFooter();
        $viewAccueil = new ViewAccueil();

        //Créeation du controller
        $controller = new ControllerAccueil($modelUser,$viewHeader,$viewFooter,$viewAccueil);

        //Lancer le rendu de la page
        $controller->render();
        break;
    case BASE_URL."moncompte" :
        //Création des View
        $viewHeader = new ViewHeader();
        $viewFooter = new ViewFooter();
        $viewCompte = new ViewCompte();

        $controller = new ControllerCompte($viewHeader,$viewFooter,$viewCompte);
        $controller->render();
        break;
    case BASE_URL."deconnexion" :
        deconnect();
        break;
    default :
        http_response_code(404);
        echo ("<h1>Error 404 Not Found !</h1>");
}