<?php
// Pas la peine de démarrer le Session. Chaque page incluant le header.php le fait déjà.

//Variable d'affiche pour le menu de navigation
$nav = "";

//Vérifier si quelqu'un est connecté
if(isset($_SESSION['id'])){
    //Mise à jour du menu de navigation : ajout du bouton de déconnexion
    $nav = "<a href='deco.php'>Se Déconnecter</a>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <nav>
            <?php echo($nav) ?>
        </nav>
    </header>