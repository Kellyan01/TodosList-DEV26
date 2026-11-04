<?php
class ViewHeader extends View{
    //ATTRIBUT
    private string $title = "";

    //SETTER ET GETTER
    public function getTitle():string{
        return $this->title;
    }
    public function setTitle(string $newTitle):self{
        $this->title = $newTitle;
        return $this;
    }

    //METHODS
    // Pas la peine de démarrer le Session. Chaque page incluant le header.php le fait déjà.
    public function display():void{

    ?>

        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo($this->getTitle()) ?></title>
        </head>
        <body>
            <header>
                <nav>
                    <a href="<?php echo(BASE_URL) ?>">Accueil</a>
                    <?php
                        //Vérifier si quelqu'un est connecté
                        if(isset($_SESSION['id'])){
                            //Mise à jour du menu de navigation : ajout du bouton de déconnexion
                    ?>
                            <a href='<?php echo(BASE_URL) ?>moncompte'>Mon Compte</a>
                            <a href='<?php echo(BASE_URL) ?>deconnexion'>Se Déconnecter</a>
                    <?php
                        }
                    ?>
                </nav>
            </header>

    <?php
    }
}