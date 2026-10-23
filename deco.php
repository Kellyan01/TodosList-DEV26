<?php
//Démarrer la session
session_start();

//Supprimer la session
session_destroy();

//Redirection HTTP
header('Location:index.php');
exit;