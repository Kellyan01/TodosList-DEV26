<?php
/*
PROJET TODOS LIST
*/
//Démarrage de la session
session_start();

//Importer les ressources
include './Controller.php';
include '../model/Model.php';
include('../view/View.php');
include '../model/ModelUser.php';
include('../view/ViewHeader.php');
include('../view/ViewAccueil.php');
include('../view/ViewFooter.php');

//Class ControllerAccueil
class ControllerAccueil extends Controller{
    //ATTRIBUT
        
    //CONSTRUCTEUR
    public function __construct(ModelUser $newModelUser, ViewHeader $newViewHeader, ViewFooter $newViewFooter, ViewAccueil $newViewAccueil){
        $this->addModel('modelUser', $newModelUser);
        $this->addView('header', $newViewHeader);
        $this->addView('footer', $newViewFooter);
        $this->addView('accueil',$newViewAccueil);
    }

    //METHODS
    //ALGORITHME D'INSCRIPTION D'UN UTILISATEUR
    public function registerUser():void{
        //1) Vérifier que je reçoive un formulaire d'inscription
        if(isset($_POST['submit'])){

            //2) Vérifier que tous les champs sont remplis
            if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['password'])){
                
                //3) Vérifier le format de mes données
                // 3.a) Vérification de l'email
                if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){

                    // 3.b) Vérification du mot de passe
                    //mb_strlen() est plus fiable que strlen(), car il compte vraiment le nombre de caractère, et non la taille en octet
                    if(mb_strlen($_POST['password'],'UTF-8')>= 12){
                        
                        // 4) Nettoyage des données avec trim() (sauf le mot de passe)
                        $prenom = trim($_POST['prenom']);
                        $nom = trim($_POST['nom']);
                        $email = trim($_POST['email']);

                        // 5) Hasher le mot de passe
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                        //EXPLOITATION DES DONNEES
                        // -> Les étapes pour enregistrer l'utilisateur
                        $data = $this->getModels()['modelUser']->setEmail($email)->findOneByEmail();

                        //Vérifier si le tableau reçu est vide ou pas
                        if(empty($data)){
                                //Lancement de l'insertion en fournissant les données à insérer
                                $this->getModels()['modelUser']->setFirstname($prenom)->setLastname($nom)->setPassword($password)->addUser();

                                //E. Message de confirmation que je passe à la View
                                $this->getViews()['accueil']->setMessage("L'utilisateur a été enregistré :  {$prenom} {$nom} - {$email}");

                            }else{
                                // Message d'erreur
                                $this->getViews()['accueil']->setMessage("Cette email est déjà utilisé par un autre compte.");
                            }

                    }else{
                        // Message d'erreur si le mot de passe est inférieur à 12 caractères
                        $this->getViews()['accueil']->setMessage("Votre mot de passe doit faire au moins 12 caractères.");
                    }

                }else{
                    // Message d'erreur si l'email n'est pas au bon format
                    $this->getViews()['accueil']->setMessage("Votre email n'est pas au bon format.");
                }

            }else{
                // Message d'erreur si l'un des champs n'est pas remplis
                $this->getViews()['accueil']->setMessage("Veuillez remplir tous les champs du formulaire.");
            }

        }
    }

    //ALGORITHME DE CONNEXION
    function connectUser(){
        //1) Vérifier que je reçois un formulaire de connexion
        if(isset($_POST['submitCo'])){

            //2) Vérifier que les champs sont remplis
            if(!empty($_POST['emailCo']) && !empty($_POST['passwordCo'])){
                //3) Vérifier le format des données
                if(filter_var($_POST['emailCo'],FILTER_VALIDATE_EMAIL)){
                    //4) Nettoyer les données : trim()
                    $email = trim($_POST['emailCo']);

                    //Vérifier qu'un compte existe
                    $data = $this->getModels()['modelUser']->setEmail($email)->findOneByEmail();

                    //F. Vérifier si les données sont vide ou non
                    if(!empty($data)){
                        //G. Vérifier si les mot de passe correspondent
                        if(password_verify($_POST['passwordCo'],$data["password"])){
                            //H. Connecter l'utilisateur
                            $_SESSION['role'] = $data['role'];
                            $_SESSION['id'] = $data['id_user'];
                            $_SESSION['nom'] = $data['lastname'];
                            $_SESSION['prenom'] = $data['firstname'];
                            $_SESSION['email'] = $data['email'];
                            $_SESSION['createdAt'] = $data['created_at'];

                            //I. Afficher le message de confirmation
                            $this->getViews()['accueil']->setMessageCo("{$_SESSION['prenom']} {$_SESSION['nom']} est bien connecté !");

                        }else{
                            //Message d'erreur
                            $this->getViews()['accueil']->setMessageCo("Email et/ou Mot de Passe incorrect.");
                        }

                    }else{
                        //Message d'erreur
                        $this->getViews()['accueil']->setMessageCo("Email et/ou Mot de Passe incorrect.");
                    }
                }else{
                    //Message d'erreur
                    $this->getViews()['accueil']->setMessageCo('Vous devez entrer un email valide');
                }
            }else{
                // Message d'erreur
                $this->getViews()['accueil']->setMessageCo('Veuillez remplir tous les champs');
            }
        }
    }

    //ALGORITHME D'AFFICHAGE DES UTILISATEURS
    function displayUsers(){
        //D. Récupérer le tableau de données renvoyé par la BDD, et l'envoyer à la View avec setData
        $this->getViews()['accueil']->setData($this->getModels()['modelUser']->findAll());
    }

    function render():void{
        //Lancer le traitement du formulaire d'inscriptio
        $this->registerUser();

        //Lancer le traitement du formualire de connexion
        $this->connectUser();

        //Lancer l'affichage de tous les utilisateurs
        $this->displayUsers();

        //Affichage du Header
        $this->getViews()['header']->setTitle("Accueil")->display();

        //Affichage du contenu
        $this->getViews()['accueil']->display();

        //Affichage du footer
        $this->getViews()['footer']->display();
    }
}

//Création du Model à exploiter
$modelUser = new ModelUser(new PDO("mysql:host=localhost;dbname=todos","root","root"));

//Création des View
$viewHeader = new ViewHeader();
$viewFooter = new ViewFooter();
$viewAccueil = new ViewAccueil();

//Créeation du controller
$controllerAccueil = new ControllerAccueil($modelUser,$viewHeader,$viewFooter,$viewAccueil);

//Lancer le rendu de la page
$controllerAccueil->render();
?>