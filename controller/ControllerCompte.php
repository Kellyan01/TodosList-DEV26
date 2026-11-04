<?php
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
    function render():void{
        //Affichage du Header
        $this->getViews()['header']->setTitle("Mon Compte")->display();

        //Affichage du contenu
        $this->getViews()['compte']->display();

        //Affichage du footer
        $this->getViews()['footer']->display();
    }
}
?>