<?php
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
            // 2) Normalisation des données avec trim() (sauf le mot de passe) : on enlève les espaces en début et fin de chaîne de caractère avant de tester les champs vides. Ainsi on évite d'enregistrer un nom ou un prenom vide en BDD
            $prenom = trim($_POST['prenom']);
            $nom = trim($_POST['nom']);
            $email = trim($_POST['email']);

            //3) Vérifier que tous les champs sont remplis
            if(!empty($prenom) && !empty($nom) && !empty($email) && !empty($_POST['password'])){
                
                
                //4) Vérifier le format de mes données
                // 4.a) Vérification de l'email
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){

                    // 4.b) Vérification du mot de passe
                    //mb_strlen() est plus fiable que strlen(), car il compte vraiment le nombre de caractère, et non la taille en octet
                    if(mb_strlen($_POST['password'],'UTF-8')>= 12){

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
    public function connectUser():void{
        //1) Vérifier que je reçois un formulaire de connexion
        if(isset($_POST['submitCo'])){
            //2) Normalisation des données : trim()
            $email = trim($_POST['emailCo']);

            //3) Vérifier que les champs sont remplis
            if(!empty($email) && !empty($_POST['passwordCo'])){
                //3) Vérifier le format des données
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                    //4) Vérifier qu'un compte existe
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
    public function displayUsers():void{
        //D. Récupérer le tableau de données renvoyé par la BDD, et l'envoyer à la View avec setData
        $this->getViews()['accueil']->setData($this->getModels()['modelUser']->findAll());
    }

    public function render():void{
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
