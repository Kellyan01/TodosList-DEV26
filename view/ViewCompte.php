<?php
class ViewCompte extends View{
    //ATTRIBUT
    
    //GETTER ET SETTER
    
    //METHODS
    function display():void{
?>
        <main>
            <h1>Mon Compte</h1>
            <h2><?php echo($_SESSION["nom"]." ".$_SESSION["prenom"]); ?></h2>
            <p>Email : <?php echo($_SESSION["email"]) ?></p>
            <p>Role : <?php echo($_SESSION["role"]) ?></p>
            <p>crée le : <?php echo($_SESSION["createdAt"]) ?></p>                           
        </main>
<?php
    }
}
