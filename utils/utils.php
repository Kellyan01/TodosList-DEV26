<?php
//Fichier de fonction utilitaires

//Création de l'objet de connexion PDO
function connect(){
    try{
        return new PDO("mysql:host={$_ENV['dbhost']};dbname={$_ENV['dbname']}",$_ENV['dblogin'],$_ENV['dbpassword']);
    }catch(Exception $error){
        //Message pour l'utilisateur : le bon code HTTP et un message neutre
        http_response_code(500);
        die("<h1>Service momentanément indisponible. Veuillez réessayer plus tard.</h1>");
    }
}