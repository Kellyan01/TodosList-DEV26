<?php
//Fichier de fonction utilitaires

//Création de l'objet de connexion PDO
function connect(){
    try{
        return new PDO("mysql:host={$_ENV['dbhost']};dbname={$_ENV['dbname']}",$_ENV['dblogin'],$_ENV['dbpassword']);
    }catch(Exception $error){
        http_response_code(500);
        //En mode Debug (environnement de développement), laisse le message de debug pour le développeur
        if ($_ENV['debug']) {
            die("<h1>Erreur BDD : " . htmlentities($error->getMessage()) . "</h1>");
        }
        //Message pour l'utilisateur : le bon code HTTP et un message neutre
        die("<h1>Service momentanément indisponible. Veuillez réessayer plus tard.</h1>");
    }
}