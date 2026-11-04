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
            die(json_encode(["code" => 500, "message" => $error->getMessage()]));
        }
        //Message pour l'utilisateur : le bon code HTTP et un message neutre
        die(json_encode(["code" => 500, "message" => "Service momentanément indisponible. Veuillez réessayer plus tard."]));
    }
}