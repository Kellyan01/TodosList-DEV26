<?php
/*
PROJET TODOS LIST
*/
//Démarrage de la session
session_start();

// Déclarer mes variables d'affichage
$message = '';
$messageCo = '';
$userList = '';

//ALGORITHME D'INSCRIPTION D'UN UTILISATEUR
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
                try{
                    //A. Instancier (construire) un objet PDO
                    $pdo = new PDO('mysql:host=localhost;dbname=todos','root','root',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                    //A.BIS : Vérifier si l'email utiliser pour l'enregistrement est disponible
                    //Préparer une requête SELECT pour récupérer les données d'un compte utilisant l'email
                    $request = $pdo->prepare('SELECT u.firstname, u.lastname, u.email FROM users u WHERE u.email = ?');

                    //BindParam() pour injecter l'email dans la requête
                    $request->bindParam(1,$email,PDO::PARAM_STR);

                    //Exécuter la requête
                    $request->execute();

                    //Récupérer la réponse
                    //ici fetch() uniquement, car je n'ai besoin de trouver qu'une seule ligne, et donc qu'un seul et unique tableau associatif au maximum
                    $data = $request->fetch(PDO::FETCH_ASSOC);

                    //Vérifier si le tableau reçu est vide ou pas
                    if(empty($data)){
                        //B. Préparer ma requête d'insertion en BDD
                        $request = $pdo->prepare('INSERT INTO users (firstname, lastname, email, `password`) VALUES(?, ?, ?, ?)');

                        //C. BindParam pour remplir les ? au sein de la requête
                        $request->bindParam(1,$prenom,PDO::PARAM_STR);
                        $request->bindParam(2,$nom,PDO::PARAM_STR);
                        $request->bindParam(3,$email,PDO::PARAM_STR);
                        $request->bindParam(4,$password,PDO::PARAM_STR);

                        //D. Envoyer la requête à la BDD, pour exécution
                        $request->execute();

                        //E. Message de confirmation
                        $message = "L'utilisateur a été enregistré :  {$prenom} {$nom} - {$email}";

                    }else{
                        // Message d'erreur
                        $message = "Cette email est déjà utilisé par un autre compte.";
                    }

                }catch(EXCEPTION $error){
                    die($error->getMessage());
                }

            }else{
                // Message d'erreur si le mot de passe est inférieur à 12 caractères
                $message = "Votre mot de passe doit faire au moins 12 caractères.";
            }

        }else{
            // Message d'erreur si l'email n'est pas au bon format
            $message = "Votre email n'est pas au bon format.";
        }

    }else{
        // Message d'erreur si l'un des champs n'est pas remplis
        $message = "Veuillez remplir tous les champs du formulaire.";
    }

}

//ALGORITHME DE CONNEXION
//1) Vérifier que je reçois un formulaire de connexion
if(isset($_POST['submitCo'])){

    //2) Vérifier que les champs sont remplis
    if(!empty($_POST['emailCo']) && !empty($_POST['passwordCo'])){
        //3) Vérifier le format des données
        if(filter_var($_POST['emailCo'],FILTER_VALIDATE_EMAIL)){
            //4) Nettoyer les données : trim()
            $email = trim($_POST['emailCo']);

            //Vérifier qu'un compte existe
            try{
                //A. Objet de connexion PDO
                $pdo = new PDO('mysql:host=localhost;dbname=todos','root','root',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                //B. Préparation de la requête
                $request = $pdo->prepare('SELECT u.id_user, u.firstname, u.lastname, u.email, u.`password`, u.created_at, r.`role` FROM users u INNER JOIN roles r ON u.id_role = r.id_role WHERE u.email = ?');

                //C. BindParam
                $request->bindParam(1,$email,PDO::PARAM_STR);

                //D. Exécution de la requête
                $request->execute();

                //E. Récupération des données dans un tableau associatif
                $data = $request->fetch(PDO::FETCH_ASSOC);

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
                        $messageCo = "{$_SESSION['prenom']} {$_SESSION['nom']} est bien connecté !";

                    }else{
                        //Message d'erreur
                        $messageCo = "Email et/ou Mot de Passe incorrect.";
                    }

                }else{
                    //Message d'erreur
                    $messageCo = "Email et/ou Mot de Passe incorrect.";
                }

            }catch(EXCEPTION $error){
                die($error->getMessage());
            }
        }else{
            //Message d'erreur
            $messageCo = 'Vous devez entrer un email valide';
        }
    }else{
        // Message d'erreur
        $messageCo = 'Veuillez remplir tous les champs';
    }
}

//ALGORITHME D'AFFICHAGE DES UTILISATEURS
try{
    //A. Instancier (construire) un objet PDO
    $pdo = new PDO('mysql:host=localhost;dbname=todos','root','root',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


    //B. Préparer la requête SELECT
    $request = $pdo->prepare('SELECT u.firstname, u.lastname, u.email FROM users u');

    //C. Exécuter la requête
    $request->execute();

    //D. Récupérer le tableau de données renvoyé par la BDD
    $data = $request->fetchAll(PDO::FETCH_ASSOC);

    //E. Formattage de mes donnée utilisateurs et les mettre dans ma variable d'affichage $userList
    foreach($data as $user){
        $userList = $userList."<li>{$user['firstname']} {$user['lastname']} - {$user['email']}</li>";
    }

}catch(EXCEPTION $error){
    die($error->getMessage());
}

//J'inclus le header en utilisant son chemin relatif
include('header.php');
?>


    <main>
        <h1>Page d'Accueil</h1>
        <?php 
            //Vérifier si personne n'est connecté
            // Si c'est le cas, j'affiche les formulaires d'inscription et connexion
            if(!isset($_SESSION['id'])){
        ?>
                <section>
                    <h2>Inscription</h2>
                    <form action="" method="post">
                        <label for="prenom">Prenom <input type="text" name="prenom" id="prenom"></label>
                        <label for="nom">Nom <input type="text" name="nom" id="nom"></label>
                        <label for="email">Email <input type="text" name="email" id="email"></label>
                        <label for="password">Mot de Passe <input type="text" name="password" id="password"></label>
                        <input type="submit" name="submit">
                    </form>
                    <p>
                        <?php echo($message) ?>
                    </p>
                </section>
                <section>
                    <h2>Connexion</h2>
                    <form action="" method="post">
                        <label for="emailCo">Email <input type="text" name="emailCo" id="emailCo"></label>
                        <label for="passwordCo">Mot de Passe <input type="text" name="passwordCo" id="passwordCo"></label>
                        <input type="submit" name="submitCo">
                    </form>
                </section>
        <?php
            }
        ?>
        <!-- Affichage du message de confirmation de connexion -->
        <p>
            <?php echo($messageCo) ?>
        </p>
        <section>
            <h2>Listes des utilisateurs</h2>
            <ul>
                <?php echo($userList) ?>
            </ul>
        </section>
    </main>

<?php
    //J'inclus le footer en utilisant son chemin relatif
    include('footer.php');
?>