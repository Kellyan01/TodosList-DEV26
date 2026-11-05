<?php
//Namespace : dossier vituel dans lequel je range la class contenu dans ce fichier
namespace TodosList\Controller;

use TodosList\View\ViewHeader;
use TodosList\View\ViewFooter;
use TodosList\View\ViewCompte;

//Class ControllerCompte
class ControllerCompte extends Controller{
    //ATTRIBUT
        
    //CONSTRUCTEUR
    public function __construct(ViewHeader $newViewHeader, ViewFooter $newViewFooter, ViewCompte $newViewCompte){
        $this->addView('header', $newViewHeader);
        $this->addView('footer', $newViewFooter);
        $this->addView('compte',$newViewCompte);
    }

    //METHODS
    public function render():void{
        //Vérifier si on accède à cette page sans être connecté. Si c'est le cas, on redirige vers l'accueil
        if (!isset($_SESSION['id'])) {
            header('Location:'.BASE_URL);
            exit;
        }
        //Affichage du Header
        $this->getViews()['header']->setTitle("Mon Compte")->display();

        //Affichage du contenu
        $this->getViews()['compte']->display();

        //Affichage du footer
        $this->getViews()['footer']->display();
    }
}
