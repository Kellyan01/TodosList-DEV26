<?php
function deconnect(){
    //Supprimer la session
    session_destroy();

    //Redirection HTTP
    header('Location:/TodosList/');
    exit;
}