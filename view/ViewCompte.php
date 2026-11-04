<?php
class ViewCompte extends View{
    //ATTRIBUT
    
    //GETTER ET SETTER
    
    //METHODS
    function display():void{
        // Ne pas oublier htmlentities() pour éviter les injection de code (faille XSS)
?>
        <main>
            <h1>Mon Compte</h1>
            <h2><?php echo(htmlentities($_SESSION["nom"])." ".htmlentities($_SESSION["prenom"])); ?></h2>
            <p>Email : <?php echo(htmlentities($_SESSION["email"])) ?></p>
            <p>Role : <?php echo(htmlentities($_SESSION["role"])) ?></p>
            <p>crée le : <?php echo(htmlentities($_SESSION["createdAt"])) ?></p>                           
        </main>
<?php
    }
}
