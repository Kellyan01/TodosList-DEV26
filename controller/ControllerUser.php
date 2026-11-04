<?php
//Controller qui orchestre les endpoints concernant les utilisateurs
class ControllerUser extends Controller{
    //ATTRIBUT

    //CONSTRUCTEUR

    //GETTER ET SETTER

    //METHOD
    public function list(array $tabMethods):void{
        // I. Mise en place des header()
        //Controle de l'origin : Qui a le droit de demander service : ici TOUT LE MONDE
        header("Access-Control-Allow-Origin: *");

        // Format des données envoyées : Dans quelle langue je parle : ici en JSON, avec caractères encodés en UTF-8
        header("Content-Type: application/json; charset=UTF-8");

        // Méthodes autorisées : Quel est le but de la route : ici lire des données
        header("Access-Control-Allow-Methods: GET");

        // Entêtes autorisées pour le Client : qu'est-ce que le client a le droit de m'envoyer
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        // II. Test des METHODS HTTP
        $this->testMethods($tabMethods);

        // III. Récupération de la liste de users
        try{
            //Appel du model pour obtenir la liste des utilisateurs
            $data = $this->getModels()['modelUser']->findAll();

            //Réponse HTTP
            http_response_code(200);

            //Encodage et envoie des datas
            echo(json_encode(["code"=>200, "users"=>$data]));
            exit;

        }catch(Exception $error){
            http_response_code(500);
            //En mode Debug (environnement de développement), laisse le message de debug pour le développeur
            if ($_ENV['debug']) {
                die(json_encode(["code" => 500, "message" => $error->getMessage()]));
            }
            //Message pour l'utilisateur : le bon code HTTP et un message neutre
            die(json_encode(["code" => 500, "message" => "Service momentanément indisponible. Veuillez réessayer plus tard."]));
        }
    }
}