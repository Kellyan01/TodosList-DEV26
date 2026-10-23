<?php
/*
PROJET TODOS LIST
*/
// Déclarer mes variables d'affichage
$message = '';
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

    print_r($data);

    //E. Formattage de mes donnée utilisateurs et les mettre dans ma variable d'affichage $userList
    foreach($data as $user){
        $userList = $userList."<li>{$user['firstname']} {$user['lastname']} - {$user['email']}</li>";
    }

}catch(EXCEPTION $error){
    die($error->getMessage());
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
    <main>
        <h1>Page d'Accueil</h1>
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
            <h2>Listes des utilisateurs</h2>
            <ul>
                <?php echo($userList) ?>
            </ul>
        </section>
    </main>
</body>
</html>